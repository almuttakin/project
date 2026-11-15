# -*- coding: utf-8 -*-
"""
Script Generator Laporan Final Tugas Akhir Lengkap (.docx)
Judul Resmi: AUTO GENERATE CONFIG XML PADA TIMBANGAN PTPN 4 REGIONAL 3 UNTUK MELEMPAR DATA KE SISTEM SAP KANTOR PUSAT
Penulis: Muhammad Nur Almuttakin
Sesuai Dokumen Analisis Sistem (BAB III & BAB IV) Lengkap dengan Format Tabel Resmi & 2 Aktor Sistem.
"""

import os
import sys
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

def create_full_report():
    doc = docx.Document()

    # Margin Standar Skripsi/Tugas Akhir: Kiri 4cm (1.57 in), Atas 3cm (1.18 in), Kanan 3cm (1.18 in), Bawah 3cm (1.18 in)
    for section in doc.sections:
        section.top_margin = Inches(1.18)
        section.bottom_margin = Inches(1.18)
        section.left_margin = Inches(1.57)
        section.right_margin = Inches(1.18)

    # Style Dasar
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(12)
    normal_style.font.color.rgb = RGBColor(30, 41, 59)
    normal_style.paragraph_format.line_spacing = 1.5
    normal_style.paragraph_format.space_after = Pt(6)

    def add_p(text, bold_prefix="", italic=False, align=WD_ALIGN_PARAGRAPH.JUSTIFY, space_after=6, space_before=0, indent=0.4):
        p = doc.add_paragraph()
        p.alignment = align
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.line_spacing = 1.5
        if indent > 0:
            p.paragraph_format.first_line_indent = Inches(indent)
        if bold_prefix:
            r_bold = p.add_run(bold_prefix)
            r_bold.bold = True
            r_bold.font.name = 'Times New Roman'
            r_bold.font.size = Pt(12)
        run = p.add_run(text)
        run.italic = italic
        run.font.name = 'Times New Roman'
        run.font.size = Pt(12)
        return p

    def add_bullet(text, bold_prefix=""):
        p = doc.add_paragraph(style='List Bullet')
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.line_spacing = 1.15
        if bold_prefix:
            rb = p.add_run(bold_prefix)
            rb.bold = True
            rb.font.name = 'Times New Roman'
            rb.font.size = Pt(11)
        r = p.add_run(text)
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        return p

    def add_h1(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.space_before = Pt(18)
        p.paragraph_format.space_after = Pt(12)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(14)
        run.font.color.rgb = RGBColor(15, 81, 50)
        return p

    def add_h2(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.space_before = Pt(14)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(12.5)
        run.font.color.rgb = RGBColor(11, 70, 41)
        return p

    def add_h3(text):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        p.paragraph_format.space_before = Pt(10)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.bold = True
        run.font.name = 'Times New Roman'
        run.font.size = Pt(12)
        run.font.color.rgb = RGBColor(30, 41, 59)
        return p

    def set_cell_background(cell, fill_hex):
        tcPr = cell._element.get_or_add_tcPr()
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
        tcPr.append(shd)

    def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
        tcPr = cell._element.get_or_add_tcPr()
        tcMar = parse_xml(f'<w:tcMar {nsdecls("w")}><w:top w:w="{top}" w:type="dxa"/><w:bottom w:w="{bottom}" w:type="dxa"/><w:left w:w="{left}" w:type="dxa"/><w:right w:w="{right}" w:type="dxa"/></w:tcMar>')
        tcPr.append(tcMar)

    def set_table_borders(table, color="D1D5DB", sz="4", val="single"):
        tblPr = table._element.xpath('w:tblPr')
        if tblPr:
            borders = parse_xml(f'<w:tblBorders {nsdecls("w")}><w:top w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/><w:bottom w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/><w:left w:val="none"/><w:right w:val="none"/><w:insideH w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/><w:insideV w:val="none"/></w:tblBorders>')
            tblPr[0].append(borders)

    def create_styled_table(headers, data_rows, col_widths, aligns=None):
        table = doc.add_table(rows=len(data_rows) + 1, cols=len(headers))
        table.alignment = WD_TABLE_ALIGNMENT.CENTER
        set_table_borders(table)

        # Header
        hdr_row = table.rows[0]
        hdr_tr = hdr_row._tr.get_or_add_trPr()
        hdr_tr.append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))
        for idx, (head_text, width) in enumerate(zip(headers, col_widths)):
            cell = hdr_row.cells[idx]
            cell.width = Inches(width)
            set_cell_background(cell, "0F5132")
            set_cell_margins(cell, top=120, bottom=120, left=140, right=140)
            p = cell.paragraphs[0]
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            run = p.add_run(head_text)
            run.bold = True
            run.font.name = "Times New Roman"
            run.font.size = Pt(10)
            run.font.color.rgb = RGBColor(255, 255, 255)

        # Rows
        for r_idx, row_data in enumerate(data_rows):
            row = table.rows[r_idx + 1]
            bg_hex = "F8FAFC" if (r_idx % 2 == 1) else "FFFFFF"
            for c_idx, (val, width) in enumerate(zip(row_data, col_widths)):
                cell = row.cells[c_idx]
                cell.width = Inches(width)
                set_cell_background(cell, bg_hex)
                set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
                p = cell.paragraphs[0]
                p.paragraph_format.space_before = Pt(0)
                p.paragraph_format.space_after = Pt(0)
                if aligns and c_idx < len(aligns):
                    p.alignment = aligns[c_idx]
                else:
                    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
                run = p.add_run(str(val))
                run.font.name = "Times New Roman"
                run.font.size = Pt(9.5)
                run.font.color.rgb = RGBColor(30, 41, 59)
                
        doc.add_paragraph().paragraph_format.space_after = Pt(6)
        return table

    # ================= COVER / JUDUL =================
    p_cov_top = doc.add_paragraph()
    p_cov_top.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cov_top.paragraph_format.space_before = Pt(36)
    p_cov_top.paragraph_format.space_after = Pt(12)
    r = p_cov_top.add_run("LAPORAN FINAL TUGAS AKHIR\n")
    r.bold = True
    r.font.size = Pt(16)
    r.font.color.rgb = RGBColor(15, 81, 50)

    p_cov_title = doc.add_paragraph()
    p_cov_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cov_title.paragraph_format.space_before = Pt(12)
    p_cov_title.paragraph_format.space_after = Pt(24)
    r_title = p_cov_title.add_run("AUTO GENERATE CONFIG XML PADA TIMBANGAN PTPN 4 REGIONAL 3 UNTUK MELEMPAR DATA KE SISTEM SAP KANTOR PUSAT")
    r_title.bold = True
    r_title.font.size = Pt(14)
    r_title.font.color.rgb = RGBColor(11, 70, 41)

    p_cov_sub = doc.add_paragraph()
    p_cov_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cov_sub.paragraph_format.space_before = Pt(6)
    p_cov_sub.paragraph_format.space_after = Pt(40)
    r_sub = p_cov_sub.add_run("Penerapan Integrasi AutoXML ADO Rowset Jembatan Timbang Menuju SAP ERP Enterprise Berbasis Dual-Aktor (Admin TI Regional 3 & Kepala TI PTPN Kantor Pusat Jakarta)")
    r_sub.italic = True
    r_sub.font.size = Pt(11)

    p_cov_author = doc.add_paragraph()
    p_cov_author.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cov_author.paragraph_format.space_before = Pt(40)
    p_cov_author.paragraph_format.space_after = Pt(4)
    r_by = p_cov_author.add_run("Disusun Oleh:\n")
    r_by.font.size = Pt(11)
    r_name = p_cov_author.add_run("MUHAMMAD NUR ALMUTTAKIN\n")
    r_name.bold = True
    r_name.font.size = Pt(13)

    p_cov_inst = doc.add_paragraph()
    p_cov_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cov_inst.paragraph_format.space_before = Pt(50)
    p_cov_inst.paragraph_format.space_after = Pt(24)
    r_inst = p_cov_inst.add_run("PROGRAM STUDI SISTEM INFORMASI / TEKNIK INFORMATIKA\nPT PERKEBUNAN NUSANTARA IV (PTPN IV PALMCO)\nREGIONAL III PEKANBARU & KANTOR PUSAT JAKARTA\n2026")
    r_inst.bold = True
    r_inst.font.size = Pt(11)

    doc.add_page_break()

    # ================= KATA PENGANTAR & RINGKASAN EKSEKUTIF =================
    add_h1("RINGKASAN EKSEKUTIF")
    add_p("Laporan Tugas Akhir ini menyajikan rancang bangun dan implementasi sistem berjudul \"Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat\" (AutoXML Gateway) pada PT Perkebunan Nusantara IV (PTPN IV PalmCo). Sistem ini menyelesaikan kesenjangan interoperabilitas data antara perangkat timbangan mekanik/digital lokal pada 12 unit Pabrik Kelapa Sawit (PKS) di Regional III dengan sistem Enterprise Resource Planning (ERP) SAP di Kantor Pusat.")
    add_p("Inovasi arsitektur sistem ini menerapkan pembagian hak akses dan antarmuka secara presisi berbasis 2 Aktor Utama Sistem:")
    add_bullet("Memiliki wewenang operasional penuh mencakup pencatatan data penimbangan TBS, validasi Bruto-Tarra-Netto, pengawasan konektivitas VPN 12 unit PKS, konversi otomatis berkas transaksi ke dalam struktur XML ADO Rowset berstandar korporasi, serta eksekusi pengiriman payload menuju server SAP Regional/Pusat.", "1. Admin TI Regional 3: ")
    add_bullet("Memiliki wewenang manajerial dan pemantauan eksekutif tingkat holding (Kantor Pusat Jakarta). Antarmuka aktor ini menyajikan konsolidasi data makro tonase seluruh unit PKS Regional III, pemantauan SLA gateway (99.8%), rasio kepatuhan sinkronisasi SAP ERP Holding, visualisasi spasial data hub Jakarta-Sumatera, serta tinjauan audit trail verifikasi transaksi tanpa dibebani formulir input operasional timbangan.", "2. Kepala TI PTPN Kantor Pusat (Jakarta): ")
    add_p("Metode rekayasa perangkat lunak mengadopsi model Waterfall yang terstruktur mulai dari analisis kebutuhan, perancangan sistem dengan UML (khusus 2 aktor), implementasi web berbasis framework Laravel 11 dan database MySQL, hingga pengujian Black Box Testing yang membuktikan seluruh use case berfungsi 100% valid.")

    doc.add_page_break()

    # ================= BAB I: PENDAHULUAN =================
    add_h1("BAB I\nPENDAHULUAN")
    
    add_h2("1.1 Latar Belakang Masalah")
    add_p("PT Perkebunan Nusantara IV (PTPN IV PalmCo) merupakan salah satu sub-holding BUMN perkebunan terbesar di Indonesia yang mengelola komoditas kelapa sawit secara terintegrasi dari hulu hingga hilir. Dalam rantai pasok kelapa sawit, stasiun jembatan timbang (weighbridge) pada Pabrik Kelapa Sawit (PKS) memegang peranan yang sangat vital dan strategis. Stasiun timbangan bertugas menimbang muatan Tandan Buah Segar (TBS) yang dipasok dari afdeling kebun inti, kebun plasma, maupun pihak ketiga, serta menimbang hasil olahan fraksi minyak kelapa sawit mentah (Crude Palm Oil / CPO) dan inti sawit (Palm Kernel / PK) sebelum didistribusikan.")
    add_p("Pada unit operasional PTPN IV Regional III, terdapat 12 unit PKS aktif yang tersebar di wilayah Sumatera Utara dan Riau, yaitu: PKS Pabatu, PKS Sawit Seberang, PKS Bah Jambi, PKS Mayang, PKS Tanah Hitam, PKS Sei Galuh, PKS Sei Pagar, PKS Tandun, PKS Lubuk Dalam, PKS Terantam, PKS Tanah Putih, dan PKS Sei Tapung. Setiap harinya, ribuan ton TBS masuk melalui jembatan timbang pada masing-masing unit PKS tersebut.")
    add_p("Namun demikian, berdasarkan hasil observasi dan analisis operasional pada unit Teknologi Informasi PTPN IV Regional III, ditemukan kendala mendasar pada proses integrasi data timbangan menuju sistem ERP SAP holding. Selama ini, data penimbangan lokal disimpan dalam basis data stasiun kerja masing-masing pabrik. Untuk mengirimkan data ke sistem SAP di Kantor Pusat, petugas administrasi harus melakukan rekapitulasi manual atau mengekspor berkas spreadsheet secara berkala. Proses ini tidak hanya menyita waktu (time-consuming), tetapi juga rentan terhadap human-error (salah ketik plat nomor atau angka berat), keterlambatan laporan tonase harian, serta risiko ketidaksesuaian skema XML yang dipersyaratkan oleh modul BAPI/RFC SAP.")
    add_p("Selain itu, dari perspektif tata kelola TI korporasi, terdapat kesenjangan visibilitas informasi antara operasional regional dengan kantor pusat di Jakarta. Kepala Divisi TI Kantor Pusat membutuhkan ringkasan eksekutif real-time mengenai konsolidasi tonase seluruh pabrik, keandalan jaringan private VPN antar-unit, dan persentase keberhasilan pengiriman transaksi ke SAP holding, tanpa harus mengakses menu input transaksi operasional timbangan yang rumit.")
    add_p("Berdasarkan permasalahan tersebut, dirancang dan dibangun sistem berjudul \"Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat\" yang secara otomatis mentransformasikan data penimbangan menjadi format standar XML ADO Rowset korporasi dan mengintegrasikannya secara berjenjang kepada 2 Aktor Utama: Admin TI Regional 3 dan Kepala TI PTPN Kantor Pusat Jakarta.")

    add_h2("1.2 Identifikasi Masalah")
    add_p("Berdasarkan latar belakang yang telah dipaparkan, diidentifikasi beberapa permasalahan pokok sebagai berikut:")
    add_bullet("Proses konversi data transaksi penimbangan TBS pada 12 unit PKS menuju format XML yang kompatibel dengan SAP ERP masih bersifat parsial dan manual, sehingga memperlambat alur transmisi data.", "1. Ketergantungan Proses Manual: ")
    add_bullet("Tingginya potensi kesalahan manusia (human error) pada proses rekapitulasi dan input berulang nomor tiket, bobot bruto, dan bobot tarra timbangan antar sistem.", "2. Kerentanan Integritas Data: ")
    add_bullet("Format dokumen XML lokal belum sepenuhnya seragam dengan standar skema ADO Recordset Microsoft (urn:schemas-microsoft-com:rowset) yang menjadi prasyarat modul integrasi holding.", "3. Standarisasi Format Berkas: ")
    add_bullet("Belum tersedianya antarmuka eksekutif khusus bagi Kepala TI PTPN Kantor Pusat di Jakarta untuk memantau data konsolidasi tonase 12 unit PKS, ketersediaan jaringan gateway, dan status kepatuhan SAP secara real-time.", "4. Absensi Visibilitas Tingkat Holding: ")

    add_h2("1.3 Batasan Masalah")
    add_p("Agar ruang lingkup pembahasan pada tugas akhir ini tetap terarah dan terfokus pada sasaran yang ditetapkan, maka batasan masalah ditentukan sebagai berikut:")
    add_bullet("Cakupan unit penimbangan dibatasi pada 12 unit PKS aktif di lingkungan PTPN IV Regional III (Pabatu, Sawit Seberang, Bah Jambi, Mayang, Tanah Hitam, Sei Galuh, Sei Pagar, Tandun, Lubuk Dalam, Terantam, Tanah Putih, dan Sei Tapung).", "1. Ruang Lingkup Pabrik: ")
    add_bullet("Sistem dimodelkan dan diimplementasikan secara tegas dengan membedakan hak akses dan antarmuka HANYA untuk 2 Aktor Manusia: (a) Admin TI Regional 3 untuk operasional dan transmisi data, dan (b) Kepala TI PTPN Kantor Pusat Jakarta untuk monitoring eksekutif holding.", "2. Aktor Sistem: ")
    add_bullet("Struktur berkas XML yang dihasilkan mengikuti format standar korporasi Microsoft ADO Rowset dengan namespace urn:schemas-microsoft-com:rowset dan elemen z:row.", "3. Spesifikasi XML: ")
    add_bullet("Jalur komunikasi data antar PKS menuju Gateway Regional dan Kantor Pusat disimulasikan menggunakan topologi jaringan Private IP VPN dengan protokol HTTP/REST API gateway.", "4. Infrastruktur Jaringan: ")
    add_bullet("Pengembangan aplikasi menggunakan bahasa pemrograman PHP 8.2+, basis data MySQL, dan framework Laravel 11.", "5. Lingkungan Perangkat Lunak: ")

    add_h2("1.4 Rumusan Masalah")
    add_p("Berdasarkan identifikasi dan batasan masalah di atas, maka rumusan masalah penelitian ini dirumuskan sebagai berikut:")
    add_bullet("Bagaimana merancang dan membangun sistem Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 yang mampu menghitung nilai netto secara otomatis dan mengonversi data penimbangan TBS ke format XML ADO Rowset standar perusahaan?", "1. ")
    add_bullet("Bagaimana merancang arsitektur sistem berbasis 2 aktor (Admin TI Regional 3 dan Kepala TI Kantor Pusat Jakarta) agar memberikan pemisahan wewenang operasional dan pengawasan strategis holding yang efektif?", "2. ")
    add_bullet("Bagaimana membangun antarmuka Executive Monitoring Dashboard bagi Kepala TI Kantor Pusat Jakarta yang menyajikan konsolidasi tonase, SLA gateway, dan status sinkronisasi SAP holding secara real-time?", "3. ")
    add_bullet("Bagaimana hasil pengujian fungsional sistem menggunakan metode Black Box Testing terhadap seluruh modul yang digunakan oleh kedua aktor sistem?", "4. ")

    add_h2("1.5 Tujuan Penelitian")
    add_p("Tujuan yang ingin dicapai melalui perancangan dan pembangunan sistem ini adalah:")
    add_bullet("Menghasilkan sistem informasi AutoXML Gateway berbasis web yang mengotomatisasi perhitungan berat netto dan pembuatan dokumen XML ADO Rowset yang valid sesuai standar SAP PTPN IV.", "1. Otomasi Integrasi: ")
    add_bullet("Mengimplementasikan kontrol akses dan alur kerja berbasis 2 Aktor (Admin TI Regional 3 dan Kepala TI PTPN Kantor Pusat Jakarta) untuk meningkatkan keamanan dan tata kelola data.", "2. Pemisahan Peran Berjenjang: ")
    add_bullet("Menyediakan dashboard eksekutif tingkat holding yang menyajikan informasi konsolidasi tonase, pemantauan jaringan 12 PKS, visualisasi spasial, dan log verifikasi SAP holding.", "3. Dashboard Eksekutif Holding: ")
    add_bullet("Menguji keandalan dan fungsionalitas seluruh fitur aplikasi melalui Black Box Testing guna memastikan sistem bebas dari galat (error-free) dan siap diterapkan.", "4. Verifikasi dan Pengujian: ")

    add_h2("1.6 Manfaat Penelitian")
    add_p("Penelitian dan pengembangan sistem ini diharapkan mampu memberikan manfaat nyata bagi berbagai pihak:")
    add_bullet("Mempercepat proses pengolahan data penimbangan harian di tingkat regional, meminimalkan potensi selisih angka (human error), serta mempermudah pengiriman data transaksi ke server SAP secara instan (single push maupun batch push).", "1. Bagi Admin TI Regional 3: ")
    add_bullet("Memperoleh akses cepat terhadap ringkasan data strategis holding tanpa hambatan geografis, memantau uptime SLA jalur transmisi gateway, serta memverifikasi kepatuhan data penimbangan dari seluruh pabrik di regional.", "2. Bagi Kepala TI Kantor Pusat Jakarta: ")
    add_bullet("Mendukung transformasi digitalisasi holding PTPN IV PalmCo menuju era smart agro-industry yang transparan, akuntabel, dan efisien.", "3. Bagi Korporasi PTPN IV: ")
    add_bullet("Memberikan kontribusi ilmiah dalam penerapan arsitektur integrasi sistem heterogen (legacy weighbridge to modern SAP ERP) menggunakan teknologi web modern dan metode Waterfall.", "4. Bagi Akademisi: ")

    add_h2("1.7 Sistematika Penulisan")
    add_p("Laporan tugas akhir ini disusun dengan sistematika penulisan yang terbagi ke dalam enam bab sebagai berikut:")
    add_bullet("Berisi latar belakang masalah, identifikasi masalah, batasan masalah, rumusan masalah, tujuan penelitian, manfaat penelitian, dan sistematika penulisan.", "BAB I PENDAHULUAN: ")
    add_bullet("Memuat teori-teori pendukung, penelitian terdahulu yang relevan, konsep sistem informasi, sistem basis data, UML, bahasa pemrograman, dan framework/tools yang digunakan.", "BAB II TINJAUAN PUSTAKA: ")
    add_bullet("Menjelaskan lokasi/objek penelitian, metode pengumpulan data, tahapan pengembangan sistem metode Waterfall, dan spesifikasi kebutuhan perangkat keras serta perangkat lunak.", "BAB III METODOLOGI PENELITIAN: ")
    add_bullet("Menguraikan analisis sistem berjalan, analisis kebutuhan bisnis, peran pengguna 2 aktor, analisis kebutuhan fungsional & non-fungsional, perancangan Use Case Diagram HANYA DENGAN 2 AKTOR, skenario use case, activity diagram, sequence diagram, class diagram, component diagram, ERD database, dan rancangan wireframe antarmuka.", "BAB IV ANALISIS DAN PERANCANGAN SISTEM: ")
    add_bullet("Menjelaskan implementasi basis data, implementasi kode program controller dan view, screenshot antarmuka sistem kedua aktor, serta hasil pengujian Black Box Testing.", "BAB V IMPLEMENTASI DAN PENGUJIAN: ")
    add_bullet("Menyajikan kesimpulan menyeluruh dari penelitian yang telah dilakukan serta saran-saran konstruktif untuk pengembangan sistem di masa mendatang.", "BAB VI PENUTUP: ")

    doc.add_page_break()

    # ================= BAB II: TINJAUAN PUSTAKA =================
    add_h1("BAB II\nTINJAUAN PUSTAKA")
    
    add_h2("2.1 Teori Penimbangan Pabrik Kelapa Sawit (Weighbridge Systems)")
    add_p("Jembatan timbang (weighbridge) adalah platform timbangan berskala industri berkapasitas besar (biasanya berkisar 50 hingga 60 ton) yang dipasang pada pondasi beton untuk menimbang berat kendaraan angkut beserta muatan komoditasnya. Dalam industri kelapa sawit, penimbangan TBS menerapkan rumus dasar massa:")
    add_p("Netto = Gross - Tarra", italic=True, align=WD_ALIGN_PARAGRAPH.CENTER)
    add_p("Di mana Berat Kotor (Gross/Bruto) adalah berat kendaraan truk beserta muatan TBS saat pertama kali memasuki pabrik; Berat Kosong (Tarra) adalah berat kendaraan truk setelah seluruh TBS dibongkar di loading ramp pabrik; dan Berat Bersih (Netto) adalah bobot murni kelapa sawit yang menjadi dasar perhitungan rendemen minyak dan pembayaran kepada pihak pemasok.")

    add_h2("2.2 Penelitian Terdahulu")
    add_p("Sebagai pijakan ilmiah, berikut disajikan tabel telaah pustaka terhadap beberapa penelitian terdahulu yang memiliki relevansi dengan sistem integrasi data timbangan dan sistem informasi berbasis ERP:")
    
    headers_lit = ["No", "Peneliti & Tahun", "Judul Penelitian", "Metode & Teknologi", "Hasil & Korelasi Penelitian"]
    widths_lit = [0.4, 1.3, 1.8, 1.4, 1.6]
    data_lit = [
        ["1", "Prasetyo & Pratama (2022)", "Otomatisasi Data Jembatan Timbang Berbasis IoT dan REST API", "Metode Prototyping, ESP32, RESTful API, MySQL", "Mengotomatisasi pembacaan indikator timbangan, namun belum menghasilkan format data XML standar SAP holding."],
        ["2", "Siregar et al. (2023)", "Integrasi Sistem Informasi Timbangan Sawit Terhadap SAP ERP PTPN", "Waterfall, Visual Basic, XML RPC, SAP BAPI", "Membahas integrasi penimbangan ke SAP, namun masih menggunakan arsitektur desktop konvensional tanpa dashboard eksekutif."],
        ["3", "Kurniawan & Hidayat (2024)", "Rancang Bangun Executive Information System Monitoring Produksi PKS", "Agile Scrum, PHP Laravel, PostgreSQL, GIS Leaflet", "Mengembangkan dashboard GIS untuk pimpinan, namun tidak mencakup modul bridge generator XML jembatan timbang."],
        ["4", "Almuttakin (2026 - Penelitian Ini)", "Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat", "Waterfall SDLC, Laravel 11, XML ADO Rowset, MySQL, Dual-Role", "Menjembatani kelemahan penelitian terdahulu dengan otomasi XML ADO Rowset resmi, konektivitas 12 PKS, dan 2 peran aktor tegas."]
    ]
    create_styled_table(headers_lit, data_lit, widths_lit, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h2("2.3 Konsep Sistem dan Informasi")
    add_p("Sistem dapat didefinisikan sebagai kumpulan elemen atau subsistem yang saling berinteraksi secara terkoordinasi untuk memproses masukan (input), melakukan transformasi pengolahan (processing), dan menghasilkan keluaran (output) guna mencapai suatu tujuan spesifik. Informasi adalah data mentah yang telah diproses dan ditempatkan dalam suatu konteks yang memiliki makna serta nilai guna bagi pengambilan keputusan manajerial.")

    add_h2("2.4 Basis Data Relasional & MySQL")
    add_p("Basis data relasional menyusun data ke dalam tabel-tabel dua dimensi yang terdiri atas baris (records) dan kolom (fields), di mana keterkaitan antar-entitas dihubungkan melalui relasi Primary Key dan Foreign Key. MySQL merupakan sistem manajemen basis data relasional (RDBMS) sumber terbuka berbasis Structured Query Language (SQL) yang memiliki kinerja pembacaan tinggi, stabilitas transaksi berstandar ACID (Atomicity, Consistency, Isolation, Durability), serta kompatibilitas penuh dengan framework PHP modern.")

    add_h2("2.5 Unified Modeling Language (UML)")
    add_p("Unified Modeling Language (UML) adalah bahasa standar visual yang digunakan untuk menspesifikasikan, memvisualisasikan, membangun, dan mendokumentasikan artefak dari sistem perangkat lunak berorientasi objek. Diagram UML yang digunakan dalam perancangan sistem ini mencakup:")
    add_bullet("Menggambarkan fungsionalitas sistem dari perspektif pengguna luar. Dalam sistem ini, use case diagram HANYA memuat 2 aktor manusia: Admin TI Regional 3 dan Kepala TI PTPN Kantor Pusat Jakarta.", "a. Use Case Diagram: ")
    add_bullet("Menggambarkan alur kerja aktivitas langkah demi langkah dalam suatu proses bisnis sistem.", "b. Activity Diagram: ")
    add_bullet("Menggambarkan interaksi dan urutan pengiriman pesan antar-objek dalam rentang waktu eksekusi.", "c. Sequence Diagram: ")
    add_bullet("Menggambarkan struktur statis sistem yang terdiri dari kelas-kelas, atribut, metode, dan relasi antar-kelas.", "d. Class Diagram: ")
    add_bullet("Menggambarkan organisasi fisik modul perangkat lunak, dependensi berkas, dan artefak eksekusi sistem.", "e. Component Diagram: ")

    add_h2("2.6 Bahasa Pemrograman")
    add_bullet("Bahasa skrip server-side bertenaga tinggi yang mendukung pemrograman berorientasi objek penuh, penanganan XML native melalui pustaka DOMDocument, dan performa tinggi dengan JIT compiler.", "1. PHP 8.2+: ")
    add_bullet("Bahasa pemrograman sisi klien (client-side) yang digunakan untuk merender visualisasi grafik Chart.js, peta interaktif Leaflet, pemfilteran tabel dinamis, serta manipulasi DOM antarmuka secara responsif.", "2. JavaScript (ES6+): ")
    add_bullet("Digunakan dalam lingkungan pengujian otomatisasi dan utilitas pengolahan dokumen laporan rekayasa.", "3. Python 3.13: ")

    add_h2("2.7 Framework dan Peralatan Pendukung")
    add_bullet("Framework web PHP beraliran Model-View-Controller (MVC) yang menyediakan routing ekspresif, Eloquent ORM, Blade templating engine, middleware proteksi sesi, dan migrasi skema basis data.", "1. Laravel 11: ")
    add_bullet("Framework CSS komponen antarmuka responsif yang menjamin estetika tata letak modern pada workstation desktop hingga perangkat bergerak.", "2. Bootstrap 5.3: ")
    add_bullet("Pustaka pemetaan open-source yang digunakan untuk memvisualisasikan topologi geospasial 12 unit PKS dan jalur transmisi ke Jakarta.", "3. Leaflet GIS: ")
    add_bullet("Pustaka visualisasi grafik HTML5 Canvas untuk menampilkan diagram batang distribusi tonase dan diagram donat status integrasi SAP.", "4. Chart.js: ")

    add_h2("2.8 Standar XML ADO Rowset & SAP ERP Integration")
    add_p("Standar dokumen XML yang dipersyaratkan oleh sistem penimbangan terintegrasi PTPN IV mengacu pada skema Microsoft ActiveX Data Objects (ADO) Recordset dengan deklarasi ruang nama (namespace) s:Schema, rs:rowbase, dan elemen data baris z:row. Atribut-atribut wajib dalam skema ini meliputi TRM_ID (kombinasi IP VPN dan port terminal penimbangan), TRM_CODE (kode nota transaksi unik), TRM_STS_PING (status koneksi live), dan TRM_STS_PROCESS (status keberhasilan pengiriman SAP).")

    doc.add_page_break()

    # ================= BAB III: METODOLOGI PENELITIAN =================
    add_h1("BAB III\nMETODOLOGI PENELITIAN")
    
    add_h2("3.1 Lokasi dan Objek Penelitian")
    add_p("Penelitian ini dilaksanakan pada lingkungan kerja PT Perkebunan Nusantara IV (PTPN IV PalmCo) dengan dua lokasi fokus penelitian, yaitu:")
    add_bullet("Beralamat di Jl. Rambutan No. 43, Sidomulyo Timur, Marpoyan Damai, Kota Pekanbaru, Riau. Lokasi ini merupakan pusat komando operasional wilayah yang mengoordinasikan aktivitas penimbangan pada 12 unit PKS aktif.", "1. Kantor Regional III: ")
    add_bullet("Beralamat di Gedung Agro Plaza, Jl. H. R. Rasuna Said Kav. X-2 No. 1, Kuningan Timur, Setiabudi, Jakarta Selatan. Lokasi ini merupakan sentral data center enterprise holding yang menampung server SAP ERP pusat dan menjadi basis kerja Kepala Divisi TI Holding.", "2. Kantor Pusat PTPN Holding (Jakarta): ")

    add_h2("3.2 Metode Pengumpulan Data")
    add_p("Pengumpulan data primer dan sekunder dilakukan melalui tiga teknik utama:")
    add_bullet("Melakukan peninjauan langsung terhadap mekanisme pencatatan data pada jembatan timbang PKS serta memantau konfigurasi jalur komunikasi data private IP VPN antar-unit pabrik menuju gateway server di kantor regional.", "1. Observasi Langsung (Observation): ")
    add_bullet("Melakukan tanya jawab teknis dengan Administrator TI Regional III mengenai kendala transmisi harian, serta berdiskusi dengan perwakilan divisi TI Kantor Pusat Jakarta mengenai indikator kinerja utama (KPI) yang dibutuhkan pimpinan eksekutif holding.", "2. Wawancara Mendalam (In-depth Interview): ")
    add_bullet("Mempelajari berkas log transaksi historis, berkas sampel XML resmi (Terminal_-_backup_20260708.xml), dokumentasi skema database weighbridge lokal, dan buku petunjuk teknis antarmuka SAP BAPI.", "3. Studi Dokumentasi (Documentation Study): ")

    add_h2("3.3 Metode Pengembangan Sistem (Waterfall SDLC)")
    add_p("Pengembangan perangkat lunak menerapkan System Development Life Cycle (SDLC) model Waterfall (Classic Life Cycle) menurut Roger S. Pressman (2015). Model ini dipilih karena karakteristik kebutuhan sistem integrasi enterprise ini memiliki spesifikasi teknis yang pasti, berurutan, dan menuntut standarisasi ketat:")
    add_bullet("Tahap inisiasi proyek melalui observasi dan dialog dengan pemangku kepentingan untuk mengidentifikasi domain permasalahan, batasan 2 aktor sistem, dan spesifikasi format XML ADO Rowset yang disepakati.", "1. Communication (Inisiasi & Analisis Kebutuhan): ")
    add_bullet("Menyusun estimasi penjadwalan kerja, pemetaan 12 unit PKS, alokasi infrastruktur IP VPN, dan manajemen risiko keterlambatan data.", "2. Planning (Estimasi & Penjadwalan): ")
    add_bullet("Menerjemahkan kebutuhan bisnis ke dalam diagram pemodelan berorientasi objek (Use Case 2 Aktor, Skenario, Activity, Sequence, Class, Component Diagram), skema ERD database, dan rancangan wireframe antarmuka.", "3. Modeling (Analisis & Desain Sistem): ")
    add_bullet("Mengimplementasikan rancangan ke dalam kode program web Laravel 11, pembuatan pustaka pembangun XML DOMDocument, pembuatan seeder 2 aktor, serta pengujian Black Box Testing.", "4. Construction (Coding & Testing): ")
    add_bullet("Memasang aplikasi pada server gateway lokal (Laragon/Debian VM), evaluasi umpan balik pengguna, dan serah terima sistem kepada penguji/penilai sidang tugas akhir.", "5. Deployment (Delivery & Evaluasi): ")

    doc.add_page_break()

    # ================= BAB IV: ANALISIS DAN PERANCANGAN SISTEM =================
    add_h1("BAB IV\nANALISIS DAN PERANCANGAN SISTEM")
    
    add_h2("4.1 Analisis Sistem")
    add_p("Pada tahap ini membahas tentang tahapan-tahapan di dalam metode penelitian yang digunakan untuk mengumpulkan data-data yang digunakan dalam proses pembuatan sistem \"Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat\" serta menerapkan System Development Life Cycle (SDLC) Waterfall.")
    add_p("Model ini berguna untuk menggambarkan tahapan-tahapan dalam perancangan sistem serta langkah-langkah yang harus dikerjakan dari setiap tahapan. Tahapan-tahapan yang dilakukan disesuaikan dengan model Waterfall menurut Pressman (2015) yang terdiri dari tahap communication, planning, modeling, construction, dan deployment pada PTPN IV Regional 3.")
    add_p("Pada tahap ini yang dilakukan dimulai dari tahap communication yang dilakukan untuk mendapatkan data dan informasi yang dibutuhkan yang diperoleh dari observasi dan wawancara pada Kerja Praktek sebelumnya, serta dari berbagai sumber literatur untuk mendapatkan informasi yang lengkap dan berguna dalam pembuatan sistem ini. Pada tahap ini dibagi menjadi beberapa tahapan analisis, antara lain: analisis kebutuhan bisnis, analisis kebutuhan pengguna, analisis kebutuhan fungsional, analisis kebutuhan non-fungsional, serta analisis kebutuhan sistem. Tahap selanjutnya yakni tahap planning dan modeling, dimana pada tahap ini dilakukan perancangan proses bisnis serta desain sistem secara detail sebagaimana dijabarkan pada subbab perancangan sistem. Setelah tahap desain sistem selesai, dilakukan tahap construction (implementasi) dan deployment.")

    add_h3("4.1.1 Analisis Kebutuhan Bisnis")
    add_p("Pada tahap analisis kebutuhan bisnis ini dilakukan analisis terhadap proses bisnis yang terjadi pada proses integrasi data transaksi penimbangan TBS di unit-unit PKS PTPN IV Regional 3 menuju sistem SAP Kantor Pusat. Proses bisnis dimulai dari Admin TI Regional 3 mencatat data penimbangan (Gross dan Tara) pada stasiun kerja, kemudian data tersebut diproses oleh sistem menjadi dokumen XML SAP-Compliant berstandar ADO Rowset, dikirimkan ke Server SAP Kantor Pusat, hingga hasilnya dipantau dan dilaporkan secara real-time pada Executive Dashboard Kepala TI Kantor Pusat Jakarta.")
    add_p("Diagram alur proses bisnis integrasi data timbangan ke SAP melibatkan alur komunikasi antar-entitas sebagai berikut: Admin TI Regional 3 melakukan penimbangan TBS dan mencatat Gross & Tara pada form input timbangan -> Sistem Web (BridgeController) melakukan input data & menghitung Netto = Gross - Tara -> Menyimpan transaksi timbangan dengan status Pending -> Sistem men-generate dokumen XML SAP-Compliant (ADO Rowset) -> Sistem mengirimkan payload XML via jalur integrasi VPN menuju Server SAP Kantor Pusat -> Server SAP menerima dan memvalidasi struktur data -> Jika struktur data valid, Server SAP menyimpan data dan mengirim respons HTTP 200 OK ke sistem -> Sistem meng-update status transaksi menjadi Sent dan mencatat log integrasi SAP -> Sistem menampilkan notifikasi status transaksi kepada Admin TI Regional 3 -> Kepala TI PTPN Kantor Pusat di Jakarta menerima laporan konsolidasi eksekutif dan memantau SLA ketersediaan gateway secara real-time.")

    add_p("A. Identifikasi Masalah", bold_prefix="", indent=0)
    add_p("Identifikasi masalah dilakukan dengan mengamati proses integrasi data timbangan yang terjadi pada PTPN IV Regional 3, seperti menentukan masalah dalam proses pengiriman data ke SAP, serta menentukan solusi dan tujuan yang ingin diperoleh. Sesuai dengan alur bisnis yang terjadi, maka diperoleh beberapa permasalahan yang muncul dalam proses integrasi data timbangan PKS ke sistem SAP sebagaimana disajikan pada Tabel 4.1.")

    # Tabel 4.1 Permasalahan, Dampak, dan Solusi (Persis Tabel 3.1 PDF)
    add_p("Tabel 4.1 Permasalahan, Dampak, dan Solusi", bold_prefix="")
    headers_t31 = ["Permasalahan", "Dampak", "Solusi"]
    widths_t31 = [2.0, 2.2, 2.3]
    data_t31 = [
        [
            "Konversi data transaksi timbangan ke format XML SAP masih dilakukan secara manual dan rawan human error pada penyesuaian format data.",
            "Data transaksi berisiko ditolak (reject) oleh sistem SAP Kantor Pusat sehingga transaksi harus diinput dan dikirim ulang.",
            "Membangun sistem yang dapat melakukan auto-generate dokumen XML SAP-Compliant (ADO Rowset) secara otomatis dari data transaksi timbangan."
        ],
        [
            "Belum ada dashboard terpusat untuk memantau status pengiriman data transaksi ke SAP dari seluruh unit PKS.",
            "Admin TI kesulitan mengetahui transaksi mana yang berstatus gagal (pending/reject) tanpa melakukan pengecekan manual satu per satu.",
            "Membangun modul dashboard monitoring & log integrasi SAP yang menampilkan status pengiriman secara real-time dari 12 unit PKS."
        ],
        [
            "Belum terdapat pencatatan riwayat (audit trail) yang lengkap atas setiap transaksi yang dikirim ke SAP.",
            "Sulit menelusuri riwayat pengiriman saat terjadi komplain atau selisih data dengan pihak SAP Kantor Pusat.",
            "Membangun modul Log Integrasi SAP yang mencatat nomor referensi, waktu kirim, dan status respons setiap transaksi secara transparan."
        ],
        [
            "Belum adanya antarmuka ringkas tingkat holding bagi pimpinan di Jakarta untuk melihat konsolidasi tonase dan SLA gateway.",
            "Kepala TI Kantor Pusat harus meminta rekap manual melalui email/telepon dan tidak dapat memantau SLA jaringan holding secara langsung.",
            "Membangun modul Executive Holding Dashboard khusus bagi Kepala TI Kantor Pusat Jakarta yang menyajikan konsolidasi tonase, SLA, dan status integrasi SAP tanpa form input operasional."
        ]
    ]
    create_styled_table(headers_t31, data_t31, widths_t31, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_p("B. Identifikasi Pengguna", bold_prefix="", indent=0)
    add_p("Identifikasi pengguna dilakukan dengan mengamati bagaimana karakteristik pengguna yang terlibat dalam proses integrasi data timbangan pada PTPN IV Regional 3 dan Kantor Pusat Jakarta. Pengguna sistem secara tegas DIBAGI MENJADI 2 AKTOR UTAMA: Admin TI Regional 3 dan Kepala TI PTPN Kantor Pusat (Jakarta). Pengguna memiliki pengalaman dalam mengoperasikan komputer dengan sistem operasi Windows, dengan pekerjaan sehari-hari menggunakan Microsoft Office dan peramban web (browser).")

    # Tabel 4.2 Peran Pengguna (Persis Tabel 3.2 PDF disesuaikan 2 Aktor)
    add_p("Tabel 4.2 Peran Pengguna", bold_prefix="")
    headers_t32 = ["No", "Nama Pengguna (Aktor)", "Peran dan Tanggung Jawab dalam Sistem"]
    widths_t32 = [0.5, 2.3, 3.7]
    data_t32 = [
        [
            "1.",
            "Admin TI Regional 3\n(admin.ti.regional3)",
            "1. Menginput data transaksi penimbangan TBS (plat, Gross, Tara, jam masuk/keluar)\n"
            "2. Memvalidasi perhitungan otomatis Netto = Gross - Tara\n"
            "3. Men-generate dokumen konfigurasi XML standar resmi Microsoft ADO Rowset\n"
            "4. Mengeksekusi pengiriman (push) data transaksi ke Server SAP (Satuan & Batch Push All)\n"
            "5. Memantau dashboard status integrasi dan konektivitas VPN 12 unit PKS\n"
            "6. Mengelola data master unit PKS dan meninjau log integrasi SAP yang gagal (retry)"
        ],
        [
            "2.",
            "Kepala TI PTPN Kantor Pusat (Jakarta)\n(kepala.ti.pusat)",
            "1. Memantau Executive Dashboard konsolidasi tonase makro 12 unit PKS Regional III (Ton & Kg)\n"
            "2. Memantau Service Level Agreement (SLA) ketersediaan jaringan gateway data center (99.8%)\n"
            "3. Memantau persentase kepatuhan dan rasio keberhasilan integrasi data transaksi ke SAP holding\n"
            "4. Melihat visualisasi spasial topologi data hub Regional III ke Data Center Jakarta\n"
            "5. Meninjau matriks kinerja komparasi 12 unit PKS tanpa dibebani form input timbangan\n"
            "6. Memeriksa audit ledger transaksi SAP holding dan mencetak laporan konsolidasi eksekutif"
        ]
    ]
    create_styled_table(headers_t32, data_t32, widths_t32, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.1.2 Analisis Kebutuhan Pengguna")
    add_p("Analisis kebutuhan pengguna dilakukan untuk mengetahui kebutuhan pengguna dalam proses integrasi data timbangan. Langkah yang dilakukan adalah dengan melakukan pengamatan terhadap bagaimana pengguna terlibat dalam proses integrasi serta bagaimana pengguna dapat memahami informasi yang dibutuhkan.")

    # Tabel 4.3 Kebutuhan Informasi dan Kebutuhan Data Pengguna (Persis Tabel 3.3 PDF)
    add_p("Tabel 4.3 Kebutuhan Informasi dan Kebutuhan Data Pengguna", bold_prefix="")
    headers_t33 = ["No", "Nama Pengguna (Aktor)", "Kebutuhan Informasi", "Kebutuhan Data"]
    widths_t33 = [0.4, 1.8, 2.2, 2.1]
    data_t33 = [
        [
            "1.",
            "Admin TI Regional 3",
            "1. Informasi status transaksi (Pending/Sent)\n"
            "2. Informasi hasil perhitungan otomatis Netto\n"
            "3. Informasi status konektivitas VPN per unit PKS\n"
            "4. Informasi log integrasi SAP (referensi, waktu kirim, status respons)\n"
            "5. Informasi statistik total XML dihasilkan",
            "1. Data 12 unit PKS (kode, nama, IP VPN)\n"
            "2. Data transaksi timbangan (plat kendaraan, Gross, Tara, jam)\n"
            "3. Data log integrasi SAP dan riwayat sesi login"
        ],
        [
            "2.",
            "Kepala TI PTPN Kantor Pusat (Jakarta)",
            "1. Informasi ringkasan performa konsolidasi holding (total tonase dalam Ton & Kg)\n"
            "2. Informasi persentase keberhasilan sinkronisasi SAP holding\n"
            "3. Informasi uptime SLA jaringan gateway data center holding (99.8%)\n"
            "4. Informasi sebaran tonase dan kepatuhan 12 unit PKS\n"
            "5. Informasi status jalur backbone Jakarta-Pekanbaru",
            "1. Data rekapitulasi transaksi & status integrasi SAP per periode\n"
            "2. Data agregasi tonase 12 unit PKS terpantau\n"
            "3. Data audit trail verifikasi transaksi SAP holding"
        ]
    ]
    create_styled_table(headers_t33, data_t33, widths_t33, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.1.3 Analisis Kebutuhan Fungsional")
    add_p("Kebutuhan fungsionalitas adalah suatu layanan sistem yang harus disediakan dan dapat berinteraksi dengan pengguna sistem, seperti apa saja di dalam sistem dan apa yang dapat dilakukan oleh pengguna. Sistem yang dirancang harus mampu memenuhi kebutuhan fungsionalitas sebagai berikut:")

    # Fungsi A: Input & Generate Dokumen XML (Tabel 3.4 PDF)
    add_p("A. Fungsi Input & Generate Dokumen XML Transaksi Timbangan", bold_prefix="", indent=0)
    add_p("Tabel 4.4 Fungsi Input & Generate Dokumen XML Transaksi Timbangan", bold_prefix="")
    headers_fn_a = ["Komponen Fungsi", "Deskripsi Rincian"]
    widths_fn_a = [2.0, 4.5]
    data_fn_a = [
        ["Nama Fungsi", "Input & Generate Dokumen XML Transaksi Timbangan"],
        ["Deskripsi", "Pada fungsi ini dilakukan kegiatan input data transaksi penimbangan TBS oleh Admin TI Regional 3, perhitungan Netto secara otomatis, serta generate dokumen XML SAP-Compliant (ADO Rowset)."],
        ["Kondisi Awal", "Data master 12 unit PKS telah tersedia pada basis data sistem."],
        ["Alur Normal - Aksi Pengguna", "1. Admin TI Regional 3 login ke sistem.\n2. Sistem menampilkan halaman modul Transaksi & XML Engine.\n3. Admin memasukkan plat kendaraan, lokasi PKS, jam masuk/keluar, Gross, dan Tara lalu menekan tombol 'Simpan & Auto Generate XML'."],
        ["Alur Normal - Respon Sistem", "1. Sistem memvalidasi kelengkapan nilai input.\n2. Sistem menghitung Netto = Gross - Tara secara otomatis.\n3. Sistem menyusun nomor nota transaksi otomatis (NT-KODEPKS-TIMESTAMP).\n4. Sistem membentuk dokumen XML berstandar Microsoft ADO Rowset (xmlns:rs='urn:schemas-microsoft-com:rowset' dan tag z:row).\n5. Sistem menyimpan data transaksi dan menampilkan notifikasi sukses berstatus 'Pending'."],
        ["Kondisi Akhir", "Di dalam fungsi ini dapat dilakukan penambahan data transaksi timbangan beserta hasil generate dokumen XML SAP-Compliant yang siap dikirimkan ke SAP."]
    ]
    create_styled_table(headers_fn_a, data_fn_a, widths_fn_a, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Fungsi B: Integrasi (Push) Data ke SAP (Tabel 3.5 PDF)
    add_p("B. Fungsi Integrasi (Push) Data ke SAP", bold_prefix="", indent=0)
    add_p("Tabel 4.5 Fungsi Integrasi (Push) Data ke SAP", bold_prefix="")
    data_fn_b = [
        ["Nama Fungsi", "Integrasi (Push) Data ke SAP"],
        ["Deskripsi", "Pada fungsi ini dilakukan kegiatan pengiriman dokumen XML transaksi timbangan menuju Server SAP Kantor Pusat melalui jalur integrasi VPN (baik satuan maupun Batch Push All)."],
        ["Kondisi Awal", "Data transaksi telah berstatus 'Pending' dan berkas XML telah tersusun."],
        ["Alur Normal - Aksi Pengguna", "1. Admin TI Regional 3 memilih transaksi pada tabel ledger dan menekan tombol 'Push SAP', atau menekan tombol 'Kirim Semua Antrean ke SAP'."],
        ["Alur Normal - Respon Sistem", "1. Sistem menyusun payload XML ADO Rowset.\n2. Sistem mengirimkannya melalui jalur integrasi IP VPN menuju Server SAP.\n3. Server SAP memvalidasi struktur data dan mengirimkan respons HTTP 200 OK beserta nomor dokumen referensi SAP.\n4. Sistem memperbarui status transaksi menjadi 'Sent' dan mencatat riwayat ke log integrasi SAP."],
        ["Kondisi Akhir", "Di dalam fungsi ini status transaksi ter-update menjadi 'Sent' (berhasil) atau 'Failed' (gagal, perlu ditinjau ulang)."]
    ]
    create_styled_table(headers_fn_a, data_fn_b, widths_fn_a, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Fungsi C: Monitoring & Log Integrasi SAP Regional (Tabel 3.6 PDF)
    add_p("C. Fungsi Monitoring & Log Integrasi SAP Regional", bold_prefix="", indent=0)
    add_p("Tabel 4.6 Fungsi Monitoring & Log Integrasi SAP Regional", bold_prefix="")
    data_fn_c = [
        ["Nama Fungsi", "Monitoring & Log Integrasi SAP Regional"],
        ["Deskripsi", "Pada fungsi ini dilakukan kegiatan pemantauan status integrasi serta peninjauan riwayat (audit trail) pengiriman data ke SAP di tingkat regional."],
        ["Kondisi Awal", "Terdapat rekaman data transaksi dan log integrasi pada sistem."],
        ["Alur Normal - Aksi Pengguna", "1. Admin TI Regional 3 membuka halaman Dashboard atau tab Log Integrasi SAP.\n2. Admin dapat memfilter atau mencari log berdasarkan unit PKS atau status transaksi."],
        ["Alur Normal - Respon Sistem", "1. Sistem menampilkan ringkasan status integrasi (total XML dihasilkan, jumlah Sent, antrean Pending, status konektivitas 12 PKS).\n2. Sistem menampilkan daftar log berisi nomor referensi SAP, waktu kirim, dan status respons."],
        ["Kondisi Akhir", "Di dalam fungsi ini Admin dapat melakukan pemantauan operasional dan penelusuran riwayat integrasi data timbangan ke SAP."]
    ]
    create_styled_table(headers_fn_a, data_fn_c, widths_fn_a, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Fungsi D: Executive Monitoring Holding (Khusus Kepala TI Pusat)
    add_p("D. Fungsi Executive Monitoring Holding & SLA Gateway Jakarta", bold_prefix="", indent=0)
    add_p("Tabel 4.7 Fungsi Executive Holding Monitoring & SLA Gateway", bold_prefix="")
    data_fn_d = [
        ["Nama Fungsi", "Executive Holding Monitoring & SLA Gateway Jakarta"],
        ["Deskripsi", "Pada fungsi ini disajikan pemantauan tingkat pimpinan holding atas konsolidasi tonase 12 unit PKS, keandalan SLA gateway data center, dan kepatuhan SAP holding."],
        ["Kondisi Awal", "Kepala TI Kantor Pusat Jakarta telah terautentikasi dan mengakses route /dashboard/pusat."],
        ["Alur Normal - Aksi Pengguna", "1. Kepala TI membuka Executive Monitoring Dashboard.\n2. Kepala TI meninjau 4 kartu metrik KPI holding, grafik distribusi tonase, dan topologi peta Leaflet.\n3. Kepala TI memfilter matriks kinerja 12 PKS dan meninjau audit ledger SAP.\n4. Kepala TI menekan tombol 'Cetak Laporan Eksekutif'."],
        ["Alur Normal - Respon Sistem", "1. Sistem menghitung agregasi total tonase Netto (Ton & Kg), rasio sinkronisasi SAP (%), dan uptime SLA (99.8%).\n2. Sistem merender visualisasi Chart.js dan peta spasial backbone Jakarta-Pekanbaru.\n3. Sistem menyajikan antarmuka cetak resmi (@media print) tanpa tombol navigasi operasional."],
        ["Kondisi Akhir", "Kepala TI Kantor Pusat memperoleh informasi strategis holding secara instan, transparan, dan dapat mengunduh/mencetak dokumen laporan eksekutif."]
    ]
    create_styled_table(headers_fn_a, data_fn_d, widths_fn_a, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.1.4 Analisis Kebutuhan Non-Fungsional")
    add_p("Analisis kebutuhan non-fungsional dilakukan untuk mengetahui spesifikasi kebutuhan sistem yang mendukung kebutuhan fungsional. Berikut adalah beberapa kriteria kebutuhan non-fungsional dari sistem yang dirancang sebagaimana disajikan pada Tabel 4.8.")

    # Tabel 4.8 Keterkaitan Kebutuhan Non-Fungsional Aplikasi (Persis Tabel 3.7 PDF)
    add_p("Tabel 4.8 Keterkaitan Kebutuhan Non-Fungsional Aplikasi", bold_prefix="")
    headers_t37 = ["Kriteria", "Kebutuhan Non-Fungsional"]
    widths_t37 = [1.8, 4.7]
    data_t37 = [
        [
            "Security",
            "1. Pengguna login menggunakan akun dan password terenkripsi Bcrypt sesuai peran (Admin Regional / Kepala TI Pusat).\n"
            "2. Jalur pengiriman data ke SAP menggunakan koneksi terenkripsi (IP VPN) hasil Kerja Praktek sebelumnya.\n"
            "3. Proteksi CSRF Token pada setiap formulir transmisi data dan audit trail riwayat login."
        ],
        [
            "Usability",
            "1. Antarmuka dashboard mudah dipahami oleh operator/admin di lapangan dengan pelatihan minimal.\n"
            "2. Antarmuka eksekutif dirancang bersih dan ringkas bagi pimpinan holding tanpa formulir teknis timbangan yang membingungkan."
        ],
        [
            "Accuracy",
            "1. Perhitungan Netto (Gross - Tara) dilakukan otomatis dengan presisi desimal tepat.\n"
            "2. Skema XML ADO Recordset (z:row) harus valid dan identik dengan standar perusahaan agar tidak terjadi reject oleh server SAP."
        ],
        [
            "Availability",
            "1. Sistem harus dapat diakses selama jam operasional penimbangan di setiap unit PKS (ketersediaan tinggi).\n"
            "2. Diperlukan koneksi jaringan/VPN yang stabil pada setiap unit PKS dengan target Service Level Agreement (SLA) 99.8%."
        ]
    ]
    create_styled_table(headers_t37, data_t37, widths_t37, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.1.5 Analisis Kebutuhan Sistem")
    add_p("Analisis kebutuhan sistem dilakukan guna mengetahui spesifikasi kebutuhan sistem dalam membangun sistem Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3. Spesifikasi kebutuhan sistem melibatkan analisis kebutuhan perangkat keras (hardware) dan analisis kebutuhan perangkat lunak (software).")

    add_p("A. Analisis Kebutuhan Perangkat Keras", bold_prefix="", indent=0)
    add_p("Pada tahap ini dilakukan analisis kebutuhan perangkat keras yang mampu mendukung kinerja sistem informasi ini. Berikut adalah tabel kebutuhan perangkat keras.")

    # Tabel 4.9 Kebutuhan Perangkat Keras (Persis Tabel 3.8 PDF)
    add_p("Tabel 4.9 Kebutuhan Perangkat Keras", bold_prefix="")
    headers_t38 = ["Komponen", "Spesifikasi Minimum yang Digunakan"]
    widths_t38 = [2.2, 4.3]
    data_t38 = [
        ["Processor", "Intel Core i3 (minimum) atau lebih tinggi / AMD Ryzen 3"],
        ["Memory", "4 GB RAM DDR4 atau lebih tinggi"],
        ["Harddisk", "SSD 128 GB atau lebih tinggi"],
        ["Monitor", "Resolusi 1024x768 atau lebih tinggi (Rekomendasi 1920x1080 Full HD)"],
        ["Jaringan", "Koneksi Internet/IP VPN yang stabil di setiap unit PKS (Bandwidth 10 Mbps)"]
    ]
    create_styled_table(headers_t38, data_t38, widths_t38, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT])

    add_p("B. Analisis Kebutuhan Perangkat Lunak", bold_prefix="", indent=0)
    add_p("Pada tahap analisis kebutuhan perangkat lunak ini dijelaskan aplikasi apa saja yang mendukung operasional sistem informasi ini.")

    # Tabel 4.10 Kebutuhan Perangkat Lunak (Persis Tabel 3.9 PDF)
    add_p("Tabel 4.10 Kebutuhan Perangkat Lunak", bold_prefix="")
    headers_t39 = ["Komponen", "Spesifikasi Perangkat Lunak yang Digunakan"]
    widths_t39 = [2.4, 4.1]
    data_t39 = [
        ["Sistem Operasi (Host)", "Windows 10 / Windows 11 / Server 64-bit"],
        ["Sistem Operasi (Gateway)", "Linux Debian 11 / 12 (hasil Kerja Praktek sebelumnya)"],
        ["Bahasa Pemrograman", "PHP versi 8.2+ (Framework Laravel 11) & JavaScript ES6"],
        ["Basis Data", "MySQL Community Server 8.0+ / MariaDB (Database: db_timbangan)"],
        ["Aplikasi Server", "Apache 2.4 / Nginx / Laravel Built-in Development Server"],
        ["Web Browser", "Google Chrome, Microsoft Edge, atau Mozilla Firefox versi terbaru"]
    ]
    create_styled_table(headers_t39, data_t39, widths_t39, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT])

    add_h2("4.2 Perancangan Sistem")
    add_p("Perancangan sistem merupakan suatu gambaran, perancangan, dan pembuatan skema atau pengaturan dari beberapa elemen terpisah ke dalam suatu kesatuan yang utuh serta mempunyai fungsi dan tujuan. Elemen-elemen sistem informasi dirancang dengan tujuan untuk dikomunikasikan dengan pengguna (user). Perancangan sistem ini meliputi perancangan alur proses bisnis yang akan dibangun berdasarkan kebutuhan yang teridentifikasi pada saat analisis, perancangan struktur data dan kelas sistem, serta perancangan arsitektur/komponen program yang akan dibangun.")
    add_p("Perancangan sistem pada Tugas Akhir ini berfokus pada sisi sistem informasi (aplikasi web) yaitu AutoXML Gateway, meliputi bagaimana data transaksi penimbangan Tandan Buah Segar (TBS) diinput, diproses, dikonversi menjadi dokumen XML SAP-Compliant (ADO Rowset), dikirim (push) ke server SAP Kantor Pusat, serta dipantau dan diaudit melalui aplikasi web. Berbeda dengan Laporan Kerja Praktek sebelumnya yang berfokus pada perancangan infrastruktur jaringan (Network Gateway Debian VM dan IP VPN) sebagai jalur komunikasi data, infrastruktur jaringan tersebut pada perancangan ini diposisikan sebagai komponen pendukung/eksternal (integration channel) yang menjembatani sistem informasi dengan Server SAP Kantor Pusat, dan tidak dirancang ulang pada dokumen ini.")

    add_h3("4.2.1 Tujuan Perancangan Sistem")
    add_p("Tujuan utama dari perancangan sistem Auto Generate Config XML ini adalah untuk memberikan gambaran secara umum kepada pengguna (Admin TI Regional 3, Kepala TI Kantor Pusat, dan dosen penilai/penguji) mengenai sistem informasi yang akan dibangun, serta mampu menjawab kebutuhan proses bisnis integrasi data timbangan PKS ke sistem SAP Kantor Pusat secara terstruktur, meliputi:")
    add_bullet("Alur proses bisnis sistem yang mudah dipahami oleh seluruh pemangku kepentingan, dimodelkan melalui Use Case Diagram HANYA 2 AKTOR beserta skenarionya.", "● ")
    add_bullet("Alur kerja (workflow) proses input data hingga integrasi data ke SAP, dimodelkan melalui Activity Diagram.", "● ")
    add_bullet("Urutan komunikasi antar objek/komponen sistem berdasarkan waktu, dimodelkan melalui Sequence Diagram.", "● ")
    add_bullet("Struktur data dan relasi antar entitas sistem sebagai dasar perancangan basis data, dimodelkan melalui Class Diagram.", "● ")
    add_bullet("Arsitektur modul/komponen perangkat lunak sistem sebagai acuan pengembangan aplikasi, dimodelkan melalui Component Diagram.", "● ")

    add_h3("4.2.2 Gambaran Umum Sistem yang Diusulkan")
    add_p("Sebagai gambaran umum dari sistem yang diusulkan, sistem ini merupakan aplikasi berbasis web yang dapat diakses melalui jaringan lokal/intranet/internet, sehingga Admin TI Regional 3 dan Kepala TI Kantor Pusat hanya memerlukan perangkat komputer/laptop dengan fasilitas browser untuk mengelola transaksi penimbangan TBS di 12 unit PKS PTPN IV Regional 3, mengonversinya secara otomatis menjadi dokumen XML sesuai skema integrasi SAP, mengirimkan dokumen tersebut ke sistem SAP Kantor Pusat, serta mencatat dan menampilkan status/log setiap transaksi integrasi secara berjenjang.")

    # Tabel Modul Sistem yang Diusulkan (Persis Halaman 11-12 PDF + Executive Dashboard)
    add_p("Tabel 4.11 Modul Utama Sistem yang Diusulkan", bold_prefix="")
    headers_mod = ["No", "Nama Modul", "Fungsi Utama dalam Sistem"]
    widths_mod = [0.4, 2.2, 3.9]
    data_mod = [
        [
            "1",
            "Dashboard & Monitoring Regional",
            "Menampilkan ringkasan performa integrasi secara real-time: total XML dihasilkan, status pengiriman (Sent/Pending), status konektivitas 12 unit PKS, dan peta geografis penimbangan regional."
        ],
        [
            "2",
            "Transaksi & XML Generator Engine",
            "Modul input data transaksi timbangan (plat kendaraan, lokasi PKS, jam masuk/keluar, Gross, Tara), perhitungan otomatis Netto, serta generate dokumen XML berstandar Microsoft ADO Rowset."
        ],
        [
            "3",
            "Log Integrasi SAP Regional",
            "Modul audit trail seluruh aktivitas pengiriman data ke SAP: nomor referensi SAP, waktu kirim, status respons (HTTP 200 OK / reject), dan riwayat pengiriman ulang transaksi gagal."
        ],
        [
            "4",
            "Executive Holding Dashboard",
            "Modul khusus Kepala TI Kantor Pusat Jakarta: konsolidasi tonase makro 12 PKS (Ton & Kg), pemantauan SLA gateway holding (99.8%), rasio kepatuhan SAP holding, peta topologi data hub, dan cetak laporan eksekutif."
        ]
    ]
    create_styled_table(headers_mod, data_mod, widths_mod, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_p("Aktor yang Terlibat:", bold_prefix="", indent=0)
    add_p("Dalam perancangan sistem yang diusulkan, pemodelan use case HANYA DIBATASI PADA 2 AKTOR MANUSIA SESUAI KETENTUAN PENGUJIAN TUGAS AKHIR:")
    add_bullet("Admin TI Regional 3 — Aktor manusia yang bertugas menginput transaksi penimbangan TBS, memvalidasi hasil hitung Netto, men-generate berkas konfigurasi XML ADO Rowset, mengeksekusi push data ke SAP, memantau jaringan 12 PKS, dan mengelola log integrasi lokal.", "● ")
    add_bullet("Kepala TI PTPN Kantor Pusat (Jakarta) — Aktor manusia tingkat pimpinan holding yang bertugas memantau metrik konsolidasi tonase holding, memantau SLA uptime gateway (99.8%), mengevaluasi kepatuhan SAP holding, dan mencetak laporan eksekutif holding tanpa form input timbangan manual.", "● ")
    add_bullet("Server SAP Kantor Pusat — Entitas sistem eksternal pendukung yang menerima payload XML dan mengirimkan respons konfirmasi HTTP 200 OK.", "● ")
    add_bullet("Network Gateway (Debian VM / IP VPN) — Komponen infrastruktur eksternal hasil Kerja Praktek sebelumnya yang menjadi jalur transmisi data terenkripsi.", "● ")

    add_h3("4.2.3 Perancangan Prosedur yang Diusulkan")
    add_h3("4.2.3.1 Use Case Diagram (Ditegaskan HANYA 2 Aktor)")
    add_p("Untuk mengetahui gambaran interaksi antara sistem dan aktor yang terlibat pada sistem yang diusulkan, sistem dimodelkan dengan Use Case Diagram yang HANYA MEMILIKI 2 AKTOR MANUSIA: Admin TI Regional 3 dan Kepala TI PTPN Kantor Pusat Jakarta.")
    add_p("Hubungan use case kedua aktor:")
    add_bullet("Admin TI Regional 3 berelasi dengan use case: Login Sistem, Input Transaksi Timbangan, Hitung Netto Otomatis (<<include>>), Generate Dokumen XML ADO Rowset (<<include>>), Push Payload ke Server SAP (Satuan & All), Monitoring Dashboard & Status Regional, serta Kelola Log Integrasi SAP.", "1. Asosiasi Admin TI Regional 3: ")
    add_bullet("Kepala TI PTPN Kantor Pusat Jakarta berelasi dengan use case: Login Sistem, Monitoring Executive Holding Dashboard, Pantau SLA Uptime Gateway Holding, Pantau Kepatuhan SAP Holding, Tinjau Matriks Kinerja 12 PKS, Tinjau Audit Ledger SAP Holding, dan Cetak Laporan Eksekutif Holding.", "2. Asosiasi Kepala TI Kantor Pusat: ")

    add_h3("4.2.3.2 Skenario Use Case")
    add_p("Skenario use case yang diusulkan dijabarkan secara rinci ke dalam format tabel spesifikasi kasus penggunaan sebagai berikut:")

    # Skenario 1: Input Data Transaksi Timbangan (Persis Halaman 14 PDF)
    add_p("1. Skenario Use Case Input Data Transaksi Timbangan", bold_prefix="", indent=0)
    add_p("Tabel 4.12 Skenario Use Case Input Data Transaksi Timbangan", bold_prefix="")
    headers_sc = ["Identifikasi / Alur", "Rincian Skenario"]
    widths_sc = [2.0, 4.5]
    data_sc_1 = [
        ["Nama Use Case", "Input Data Transaksi Timbangan"],
        ["Deskripsi", "Admin TI Regional 3 menginput data transaksi penimbangan TBS ke sistem melalui modul Transaksi & XML Engine."],
        ["Aktor", "Admin TI Regional 3"],
        ["Pre-Condition", "Admin telah masuk ke halaman Dashboard Sistem Informasi PKS Weighbridge."],
        ["Aksi Aktor", "1. Admin membuka modul Transaksi & XML Engine.\n2. Admin memasukkan plat kendaraan, lokasi PKS, jam masuk/keluar, Gross, dan Tara lalu menekan tombol 'Simpan & Auto Generate XML'."],
        ["Reaksi Sistem", "1. Sistem menghitung Netto = Gross - Tara secara otomatis.\n2. Sistem menyusun nomor nota otomatis dan menyimpan data transaksi.\n3. Sistem menampilkan notifikasi bahwa data berhasil disimpan dengan status 'Pending'."],
        ["Skenario Alternatif", "Jika data tidak lengkap atau format salah, sistem menampilkan pesan validasi error dan data tidak disimpan."],
        ["Post-Condition", "Data transaksi tersimpan dengan status 'Pending' dan siap digenerate menjadi dokumen XML."]
    ]
    create_styled_table(headers_sc, data_sc_1, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 2: Generate & Push Dokumen XML ke SAP (Persis Halaman 15 PDF)
    add_p("2. Skenario Use Case Generate & Push Dokumen XML ke SAP", bold_prefix="", indent=0)
    add_p("Tabel 4.13 Skenario Use Case Generate & Push Dokumen XML ke SAP", bold_prefix="")
    data_sc_2 = [
        ["Nama Use Case", "Generate & Push Dokumen XML ke SAP"],
        ["Deskripsi", "Sistem menyusun dokumen XML dari data transaksi dan mengirimkannya ke Server SAP melalui jalur integrasi."],
        ["Aktor", "Admin TI Regional 3, Sistem (otomatis), Server SAP"],
        ["Pre-Condition", "Data transaksi telah tersimpan dan berstatus 'Pending'."],
        ["Aksi Aktor", "1. Admin menekan tombol 'Push SAP' atau 'Preview XML' pada tabel historical ledger, atau menekan 'Kirim Semua Antrean ke SAP'."],
        ["Reaksi Sistem", "1. Sistem menyusun dokumen XML sesuai skema SAP-Compliant (ADO Rowset).\n2. Sistem meneruskan payload XML menuju jalur integrasi (Network Gateway).\n3. Server SAP menerima dan memvalidasi struktur data, lalu mengirim respons HTTP 200 OK.\n4. Sistem memperbarui status transaksi menjadi 'Sent' beserta nomor referensi SAP.\n5. Admin menerima notifikasi bahwa data berhasil dikirim."],
        ["Skenario Alternatif", "Jika struktur data tidak valid atau jalur integrasi terputus, sistem mencatat status 'Failed/Rejected' pada Log Integrasi SAP."],
        ["Post-Condition", "Status transaksi ter-update menjadi 'Sent' dan tercatat pada Log Integrasi SAP."]
    ]
    create_styled_table(headers_sc, data_sc_2, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 3: Monitoring Dashboard & Status (Persis Halaman 16 PDF)
    add_p("3. Skenario Use Case Monitoring Dashboard & Status", bold_prefix="", indent=0)
    add_p("Tabel 4.14 Skenario Use Case Monitoring Dashboard & Status Regional", bold_prefix="")
    data_sc_3 = [
        ["Nama Use Case", "Monitoring Dashboard & Status Regional"],
        ["Deskripsi", "Admin memantau ringkasan status integrasi secara real-time melalui modul Dashboard."],
        ["Aktor", "Admin TI Regional 3"],
        ["Pre-Condition", "Admin telah masuk ke halaman Dashboard Sistem Informasi PKS Weighbridge."],
        ["Aksi Aktor", "1. Admin membuka modul Dashboard & Monitoring."],
        ["Reaksi Sistem", "1. Sistem menampilkan total XML dihasilkan, status Sent/Pending, status konektivitas per unit PKS, dan status kesiapan server SAP."],
        ["Skenario Alternatif", "Jika data monitoring gagal dimuat, sistem menampilkan notifikasi error koneksi."],
        ["Post-Condition", "Admin memperoleh gambaran kondisi sistem operasional secara real-time."]
    ]
    create_styled_table(headers_sc, data_sc_3, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 4: Kelola Log Integrasi SAP (Persis Halaman 16-17 PDF)
    add_p("4. Skenario Use Case Kelola Log Integrasi SAP", bold_prefix="", indent=0)
    add_p("Tabel 4.15 Skenario Use Case Kelola Log Integrasi SAP", bold_prefix="")
    data_sc_4 = [
        ["Nama Use Case", "Kelola Log Integrasi SAP"],
        ["Deskripsi", "Admin meninjau riwayat/audit trail seluruh transaksi pengiriman data ke SAP."],
        ["Aktor", "Admin TI Regional 3"],
        ["Pre-Condition", "Terdapat data log transaksi yang telah/berencana dikirim pada sistem."],
        ["Aksi Aktor", "1. Admin membuka modul Log Integrasi SAP.\n2. Admin dapat memfilter/mencari log berdasarkan unit PKS atau status."],
        ["Reaksi Sistem", "1. Sistem menampilkan nomor referensi SAP, waktu kirim, dan status respons.\n2. Sistem menampilkan hasil pencarian log sesuai filter yang dipilih."],
        ["Skenario Alternatif", "Jika ditemukan transaksi berstatus gagal (Rejected), admin dapat memicu pengiriman ulang (retry)."],
        ["Post-Condition", "Admin memperoleh laporan status integrasi yang akurat."]
    ]
    create_styled_table(headers_sc, data_sc_4, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 5: Executive Holding Monitoring & SLA Tracking (Khusus Kepala TI Pusat)
    add_p("5. Skenario Use Case Executive Holding Monitoring & SLA Tracking", bold_prefix="", indent=0)
    add_p("Tabel 4.16 Skenario Use Case Executive Holding Monitoring & SLA Tracking", bold_prefix="")
    data_sc_5 = [
        ["Nama Use Case", "Executive Holding Monitoring & SLA Tracking"],
        ["Deskripsi", "Kepala TI Kantor Pusat Jakarta memantau konsolidasi tonase makro, keandalan SLA gateway holding, dan kepatuhan SAP."],
        ["Aktor", "Kepala TI PTPN Kantor Pusat (Jakarta)"],
        ["Pre-Condition", "Kepala TI telah login dan berada pada URL /dashboard/pusat."],
        ["Aksi Aktor", "1. Kepala TI membuka Executive Monitoring Dashboard.\n2. Kepala TI meninjau kartu KPI holding, grafik tonase per PKS, dan peta interaktif data hub.\n3. Kepala TI memfilter data matriks performa 12 PKS."],
        ["Reaksi Sistem", "1. Sistem menyajikan agregasi total Netto TBS (Ton & Kg), persentase sinkronisasi SAP, dan SLA uptime gateway 99.8%.\n2. Sistem merender visualisasi Chart.js dan peta Leaflet koneksi Pekanbaru ke Jakarta DC.\n3. Sistem memfilter baris tabel PKS secara instan sesuai kata kunci pencarian."],
        ["Skenario Alternatif", "Kepala TI dapat mengklik tombol pintas beralih dashboard jika memerlukan peninjauan teknis jembatan timbang."],
        ["Post-Condition", "Kepala TI memperoleh visibilitas strategis holding tanpa dibebani antarmuka form tiket operasional."]
    ]
    create_styled_table(headers_sc, data_sc_5, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 6: Cetak Laporan Eksekutif Holding
    add_p("6. Skenario Use Case Cetak Laporan Eksekutif Holding", bold_prefix="", indent=0)
    add_p("Tabel 4.17 Skenario Use Case Cetak Laporan Eksekutif Holding", bold_prefix="")
    data_sc_6 = [
        ["Nama Use Case", "Cetak Laporan Eksekutif Holding"],
        ["Deskripsi", "Kepala TI Kantor Pusat mencetak rekapitulasi data konsolidasi kinerja holding secara resmi."],
        ["Aktor", "Kepala TI PTPN Kantor Pusat (Jakarta)"],
        ["Pre-Condition", "Kepala TI berada pada Executive Dashboard."],
        ["Aksi Aktor", "1. Kepala TI menekan tombol 'Cetak Laporan Eksekutif'."],
        ["Reaksi Sistem", "1. Sistem memicu fungsi window.print() dengan menerapkan stylesheet @media print khusus dokumen resmi.\n2. Sistem menyembunyikan tombol navigasi, peta, dan elemen interaktif.\n3. Dialog cetak peramban muncul dengan format laporan konsolidasi rapi siap cetak/ekspor PDF."],
        ["Skenario Alternatif", "Jika dibatalkan oleh pengguna, peramban kembali ke tampilan dashboard tanpa perubahan data."],
        ["Post-Condition", "Dokumen cetak laporan konsolidasi eksekutif holding berhasil dihasilkan."]
    ]
    create_styled_table(headers_sc, data_sc_6, widths_sc, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.2.3.3 Activity Diagram")
    add_p("Activity Diagram menggambarkan berbagai alir aktivitas sistem yang sedang dirancang, bagaimana alir itu berawal, decision yang mungkin terjadi, dan bagaimana alir aktivitas tersebut berakhir (mengacu pada Gambar 4.3 dan Gambar 4.4 dokumen analisis):")
    add_bullet("Admin membuka modul Transaksi & XML Engine -> Admin mengisi & submit form (plat, Gross, Tara, jam masuk/keluar) -> Sistem melakukan validasi (Data lengkap & valid?) -> Jika Tidak, kembali ke form input transaksi -> Jika Ya, Sistem menghitung Netto = Gross - Tara, menyimpan data (status Pending), menyusun dokumen XML ADO Rowset -> Selesai.", "1. Activity Diagram Input Data Transaksi Timbangan: ")
    add_bullet("Admin menekan tombol Push SAP -> Sistem menyusun dokumen XML SAP-Compliant (ADO Rowset) -> Sistem mengirim payload via jalur integrasi VPN -> Server SAP menerima & memvalidasi struktur data (Struktur Data Valid?) -> Jika Tidak, Sistem mencatat status Failed pada Log Integrasi (Perlu Retry) -> Jika Ya, Server SAP mengirim respons HTTP 200 OK -> Sistem update status menjadi Sent & catat historical log -> Admin menerima notifikasi status Sent -> Selesai.", "2. Activity Diagram Generate & Push Dokumen XML ke SAP: ")
    add_bullet("Kepala TI login ke sistem -> Sistem redirect ke Executive Dashboard -> Sistem mengagregasi total tonase 12 PKS, hitung rasio SAP, hitung SLA gateway 99.8% -> Sistem render peta data hub dan grafik -> Kepala TI meninjau matriks 12 PKS dan audit ledger -> Kepala TI klik Cetak Laporan Eksekutif -> Sistem membuka jendela cetak resmi -> Selesai.", "3. Activity Diagram Executive Holding Monitoring & Cetak Laporan: ")

    add_h3("4.2.3.4 Sequence Diagram")
    add_p("Sequence diagram atau diagram urutan adalah diagram interaksi yang menekankan pada pengiriman pesan (message) dalam suatu waktu tertentu. Penamaan objek pada sequence diagram mengacu pada struktur program yang diusulkan, yaitu Blade View, BridgeController, Session/MySQL sebagai data store, dan fungsi generator XML (mengacu pada Gambar 4.5 dan Gambar 4.6 dokumen analisis):")
    add_bullet("1. Operator/Admin mengisi form no plat, Gross, Tara, jam masuk/keluar pada Dashboard Blade - Form Transaksi -> 2. Form men-submit route bridge.store() ke BridgeController -> 3. BridgeController memanggil fungsi hitung netto = gross - tarra() -> 4. BridgeController memanggil generate nota_no otomatis() -> 5. BridgeController memanggil simpan data transaksi status Pending() ke Data Store -> 6. Data Store mengonfirmasi data tersimpan() -> 7. BridgeController me-redirect dengan pesan sukses() -> 8. Dashboard Blade menampilkan notifikasi XML Pending().", "1. Sequence Diagram Input Data Transaksi & Generate XML: ")
    add_bullet("1. Operator/Admin klik tombol Push SAP() pada Tabel Historical Ledger -> 2. Ledger men-request route bridge.push(id) ke BridgeController -> 3. BridgeController memanggil susun dokumen XML SAP-Compliant() ke XML Generator Engine -> 4. XML Generator Engine mengembalikan string XML() -> 5. BridgeController memanggil kirim payload XML via jalur integrasi VPN() ke Server SAP Kantor Pusat -> 6. Server SAP merespons respons HTTP 200 OK() -> 7. BridgeController memanggil update status_sap menjadi SENT() ke Data Store -> 8. BridgeController me-redirect dengan pesan sukses() -> 9. Tabel Historical Ledger menampilkan status SENT / VERIFIED().", "2. Sequence Diagram Generate & Push Dokumen XML ke SAP: ")
    add_bullet("1. Kepala TI Pusat mengakses route /dashboard/pusat -> 2. BridgeController memanggil method pusatDashboard() -> 3. BridgeController meminta agregasi data dari model TransaksiTimbangan dan UnitPks -> 4. BridgeController menghitung tonase holding, % SAP, dan SLA 99.8% -> 5. BridgeController mengembalikan view dashboard_pusat -> 6. Peramban merender Chart.js dan peta spasial Leaflet -> 7. Kepala TI meninjau laporan dan dapat memicu print layout.", "3. Sequence Diagram Executive Holding Dashboard: ")

    add_h3("4.2.3.5 Class Diagram")
    add_p("Class Diagram digunakan untuk menggambarkan kelas-kelas di dalam sistem beserta atribut dan method-nya. Diagram ini juga memberikan gambaran sistem secara statis dan relasi antar kelas (mengacu pada Gambar 4.7 dokumen analisis):")
    add_p("Class Diagram menggambarkan enam kelas utama sistem:")
    add_bullet("Atribut: id, name, username, password, role. Method: login(), logout().", "1. User (Pengguna Sistem): ")
    add_bullet("Atribut: id, namaUnit, lokasi, ipAddressVPN. Method: getStatusKoneksi(). Relasi: 1 UnitPKS memiliki 0..* TransaksiTimbangan.", "2. UnitPKS (Data Master Unit PKS): ")
    add_bullet("Atribut: id, noPlat, idUnitPKS, jamMasuk, jamKeluar, gross, tara, netto, status. Method: hitungNetto(), simpanTransaksi(). Relasi: 1 TransaksiTimbangan menghasilkan 1 XMLDocument.", "3. TransaksiTimbangan (Entitas Transaksi Penimbangan): ")
    add_bullet("Atribut: id, idTransaksi, skemaXML, waktuGenerate, filePath. Method: generateXML(), validasiSkema(). Relasi: 1 XMLDocument tercatat pada 1 LogIntegrasiSAP.", "4. XMLDocument (Dokumen XML Hasil Generate): ")
    add_bullet("Atribut: id, idTransaksi, noReferensiSAP, waktuKirim, statusRespons, kodeHTTP. Method: catatLog().", "5. LogIntegrasiSAP (Audit Trail Pengiriman Data): ")
    add_bullet("Method: kirimPayload(XMLDocument), cekStatusKoneksi(), terimaRespons(), pusatDashboard(). Mengirimkan berkas ke SAP dan memperbarui status pada LogIntegrasiSAP.", "6. SAPGatewayService / BridgeController: ")

    add_h3("4.2.3.6 Component Diagram")
    add_p("Component adalah unit fisik yang nyata yang menjadi bagian dari deployment independent. Perancangan komponen pada aplikasi yang diusulkan mengikuti struktur berkas program Laravel yang dibangun (mengacu pada Gambar 4.8 dokumen analisis):")
    add_bullet("routes/web.php — mendefinisikan route (bridge.store, bridge.push, bridge.xml, bridge.pushAll, pusat.dashboard, login, logout, dsb.) yang mengarah ke BridgeController.php dan AuthController.php.", "● ")
    add_bullet("BridgeController.php — komponen inti backend yang menangani seluruh logika bisnis: input transaksi, perhitungan Netto, pemanggilan fungsi generator XML, modul executive dashboard, serta transmisi push ke SAP.", "● ")
    add_bullet("AuthController.php — komponen autentikasi yang memverifikasi kredensial dan melakukan redirection dinamis berdasarkan peran (Admin Regional atau Kepala TI Pusat).", "● ")
    add_bullet("dashboard.blade.php (View) — antarmuka web operasional yang menampilkan form input, tabel historical ledger, peta 12 PKS, dan log integrasi SAP kepada Admin TI Regional 3.", "● ")
    add_bullet("dashboard_pusat.blade.php (View) — antarmuka eksekutif holding yang menampilkan kartu metrik KPI makro, grafik tonase, peta data hub Jakarta, matriks 12 PKS, dan print layout kepada Kepala TI Kantor Pusat.", "● ")
    add_bullet("MySQL Data Store (db_timbangan) — media penyimpanan persisten data transaksi, data master 12 PKS, log integrasi, dan riwayat login.", "● ")
    add_bullet("buildAdoXmlString() — fungsi generator yang mengonversi data transaksi mentah menjadi dokumen XML sesuai skema Microsoft ADO Recordset resmi perusahaan.", "● ")
    add_bullet("Network Gateway (Debian VM) — komponen infrastruktur eksternal hasil Kerja Praktek sebelumnya, berperan sebagai jalur transmisi data terenkripsi menuju Server SAP.", "● ")
    add_bullet("Server SAP Kantor Pusat — sistem eksternal sebagai penerima akhir data terintegrasi.", "● ")

    add_h2("4.3 Perancangan Basis Data (Entity Relationship Diagram)")
    add_p("Basis data sistem dirancang dengan nama db_timbangan menggunakan model relasional. Relasi antar-entitas terjalin secara konsisten:")
    add_bullet("Satu unit PKS (UnitPks) memiliki banyak transaksi penimbangan (TransaksiTimbangan).", "1. ")
    add_bullet("Satu transaksi timbangan (TransaksiTimbangan) memiliki relasi jejak audit pengiriman pada (LogIntegrasiSap).", "2. ")
    add_bullet("Satu pengguna (User) memiliki relasi rekam jejak aktivitas sesi pada (RiwayatLogin).", "3. ")

    # Struktur Tabel Users
    add_p("Tabel 4.18 Struktur Tabel Pengguna (users)", bold_prefix="")
    headers_tb_u = ["Nama Kolom", "Tipe Data", "Panjang / Nilai", "Atribut / Keterangan"]
    widths_tb_u = [1.8, 1.4, 1.5, 1.8]
    data_tb_u = [
        ["id", "BIGINT", "20", "Primary Key, Auto Increment, Unsigned"],
        ["name", "VARCHAR", "255", "Nama Lengkap Pengguna"],
        ["username", "VARCHAR", "100", "Unique, Kredensial Login"],
        ["email", "VARCHAR", "255", "Unique, Alamat Email Korporat"],
        ["password", "VARCHAR", "255", "Terenkripsi Bcrypt Hash"],
        ["role", "VARCHAR", "50", "'Admin Regional' / 'Kepala TI Pusat'"],
        ["created_at / updated_at", "TIMESTAMP", "Nullable", "Waktu Pembuatan & Perubahan Data"]
    ]
    create_styled_table(headers_tb_u, data_tb_u, widths_tb_u, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT])

    # Struktur Tabel Transaksi
    add_p("Tabel 4.19 Struktur Tabel Transaksi Timbangan (transaksi_timbangans)", bold_prefix="")
    headers_tb_t = ["Nama Kolom", "Tipe Data", "Panjang / Nilai", "Atribut / Keterangan"]
    data_tb_t = [
        ["id", "BIGINT", "20", "Primary Key, Auto Increment, Unsigned"],
        ["unit_pks_id", "BIGINT", "20", "Foreign Key ke unit_pks.id (Nullable)"],
        ["nota_no", "VARCHAR", "50", "Nomor Tiket Penimbangan Unik"],
        ["no_plat", "VARCHAR", "20", "Nomor Registrasi Kendaraan Truk"],
        ["pks_loc", "VARCHAR", "100", "Nama Unit dari 12 PKS"],
        ["jam_masuk", "VARCHAR", "20", "Waktu Truk Masuk (Gross)"],
        ["jam_keluar", "VARCHAR", "20", "Waktu Truk Keluar (Tarra)"],
        ["gross", "DECIMAL", "10,2", "Berat Kotor Truk (Kg)"],
        ["tarra", "DECIMAL", "10,2", "Berat Kosong Truk (Kg)"],
        ["netto", "DECIMAL", "10,2", "Berat Bersih TBS = Gross - Tarra (Kg)"],
        ["vpn_ip", "VARCHAR", "45", "Alamat IP Terminal VPN PKS"],
        ["status_vpn", "VARCHAR", "20", "'Connected' / 'Trouble' / 'Online'"],
        ["xml_status", "VARCHAR", "20", "'Generated' / 'Pending'"],
        ["status_sap", "VARCHAR", "20", "'SENT' / 'PENDING' / 'REJECTED'"],
        ["sap_sent_at", "TIMESTAMP", "Nullable", "Waktu Berhasil Dikirim ke SAP"],
        ["created_at / updated_at", "TIMESTAMP", "Nullable", "Audit Timestamp Otomatis"]
    ]
    create_styled_table(headers_tb_t, data_tb_t, widths_tb_u, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT])

    add_h2("4.4 Perancangan Antarmuka Pengguna (Wireframe & Layout)")
    add_p("Perancangan antarmuka pengguna memisahkan secara tegas pengalaman visual kedua aktor:")
    add_bullet("Rancangan Halaman Login: Menampilkan kartu form login modern, logo PTPN IV PalmCo, serta tombol bantuan pengisian otomatis kredensial 2 aktor untuk mempermudah pengujian.", "1. ")
    add_bullet("Rancangan Dashboard Admin TI Regional 3: Menampilkan sidebar hijau tua khas PTPN, form modal penimbangan baru, widget live ADO XML generator, tabel data tiket timbangan, peta Leaflet 12 PKS regional, tabel riwayat sesi login, dan tombol beralih cepat ke Dashboard Pusat.", "2. ")
    add_bullet("Rancangan Executive Holding Dashboard: Menampilkan header eksekutif holding, 4 kartu KPI makro (Tonase Netto Terkonsolidasi, % Sinkronisasi SAP, SLA Gateway 99.8%, 12 Node PKS), grafik batang distribusi tonase, grafik donat status SAP, peta topologi data hub Jakarta-Pekanbaru, tabel matriks 12 PKS tanpa form input manual, log audit verifikasi holding, serta tombol cetak laporan eksekutif resmi.", "3. ")

    doc.add_page_break()

    # ================= BAB V: IMPLEMENTASI DAN PENGUJIAN =================
    add_h1("BAB V\nIMPLEMENTASI DAN PENGUJIAN")
    
    add_h2("5.1 Implementasi Basis Data")
    add_p("Implementasi basis data dieksekusi menggunakan skrip migrasi Laravel (PHP Artisan Migrate) pada database MySQL db_timbangan. Kolom role pada tabel users dimigrasikan menggunakan skrip migrasi 2026_11_15_000001_update_role_column_in_users_table.php untuk mendukung fleksibilitas peran 'Admin Regional' dan 'Kepala TI Pusat'.")
    add_p("Data awal pengguna (seeders) diisi melalui DatabaseSeeder.php yang menginjeksi 2 akun aktor resmi:")
    add_bullet("Username: admin.ti.regional3 | Password: admin123 | Role: Admin Regional | Nama: Administrator TI Regional III", "- Akun Aktor 1: ")
    add_bullet("Username: kepala.ti.pusat | Password: pusat123 | Role: Kepala TI Pusat | Nama: Kepala Divisi TI Kantor Pusat Jakarta", "- Akun Aktor 2: ")

    add_h2("5.2 Implementasi Kode Program dan Arsitektur Controller")
    add_h3("5.2.1 Pengontrol Autentikasi Berbasis Peran (AuthController.php)")
    add_p("AuthController menangani proses autentikasi dan secara dinamis mengalihkan (redirect) pengguna ke antarmuka yang berhak:")
    add_p("if ($user->role === 'Kepala TI Pusat' || $user->username === 'kepala.ti.pusat') {\n    return redirect()->intended(route('pusat.dashboard'));\n}\nreturn redirect()->intended(route('bridge.index'));", italic=True, indent=0.5)

    add_h3("5.2.2 Pengontrol Bisnis dan Gateway (BridgeController.php)")
    add_p("BridgeController memiliki dua metode pemuatan tampilan utama:")
    add_bullet("Menghitung data operasional lokal, status XML timbangan, riwayat login regional, dan merender tampilan resources/views/dashboard.blade.php.", "1. index(): ")
    add_bullet("Mengagregasi total gross, tarra, netto holding (Ton & Kg), persentase sinkronisasi SAP, uptime SLA jaringan, matriks performa 12 unit PKS, koordinat spasial hub data center Jakarta, dan merender tampilan resources/views/dashboard_pusat.blade.php.", "2. pusatDashboard(): ")

    add_h3("5.2.3 Mesin Pembuat Format XML ADO Rowset (buildAdoXmlString)")
    add_p("Pusat keunggulan sistem terletak pada metode buildAdoXmlString() yang menyusun dokumen XML sesuai standar Microsoft ADO Recordset yang dipersyaratkan oleh server SAP holding PTPN IV. Potongan struktur XML yang dihasilkan:")
    add_p("<xml xmlns:s='uuid:BDC6E3F0-6DA3-11d1-A2A3-00AA00C14882' xmlns:dt='uuid:C2F41010-65B3-11d1-A29F-00AA00C14882' xmlns:rs='urn:schemas-microsoft-com:rowset' xmlns:z='#RowsetSchema'>\n  <s:Schema id='RowsetSchema'> ... </s:Schema>\n  <rs:data>\n    <rs:insert>\n      <z:row TRM_ID='10.14.2.10:3306' TRM_CODE='NT-PBT-20261115' TRM_STS_PING='4' TRM_STS_PROCESS='1' ... />\n    </rs:insert>\n  </rs:data>\n</xml>", italic=True, indent=0.4)

    add_h2("5.3 Dokumentasi Antarmuka Sistem")
    add_p("Antarmuka sistem yang telah diimplementasikan dapat diakses melalui peramban web:")
    add_bullet("Menyediakan antarmuka autentikasi yang elegan dengan kartu seleksi kredensial 2 aktor secara instan untuk efisiensi demonstrasi pengujian.", "1. Halaman Login (/login): ")
    add_bullet("Menyajikan pusat kendali operasional penimbangan, tabel tiket bruto-tarra, generator XML ADO, peta spasial 12 unit pabrik regional, dan tombol pintas ke dashboard pusat.", "2. Tampilan Dashboard Admin TI Regional 3 (/dashboard): ")
    add_bullet("Menyajikan pusat komando eksekutif holding yang bersih dan berbobot, berfokus pada 4 metrik makro (Tonase Netto Terkonsolidasi, % Sinkronisasi SAP, SLA Gateway 99.8%, 12 Node PKS), grafik batang distribusi tonase, grafik donat SAP, peta data hub Jakarta-Pekanbaru, tabel performa 12 PKS, log audit verifikasi holding, serta tombol cetak laporan eksekutif.", "3. Tampilan Executive Dashboard Kepala TI Pusat (/dashboard/pusat): ")

    add_h2("5.4 Pengujian Sistem Menggunakan Black Box Testing")
    add_p("Pengujian fungsionalitas sistem dilakukan menggunakan metode Black Box Testing untuk menguji kesesuaian input dan output perangkat lunak tanpa harus melihat struktur kode internal. Pengujian dirancang secara spesifik mencakup use case untuk KEDUA AKTOR SISTEM:")

    headers_bb = ["ID Uji", "Skenario Pengujian", "Aksi Pengguna (Input)", "Hasil yang Diharapkan", "Hasil Nyata", "Status"]
    widths_bb = [0.8, 1.4, 1.4, 1.5, 0.9, 0.7]
    data_bb = [
        ["BB-01", "Login Admin Regional", "Input username 'admin.ti.regional3' & pass 'admin123'", "Sistem mengarahkan langsung ke /dashboard (Dashboard Regional 3)", "Dialihkan ke /dashboard", "Valid"],
        ["BB-02", "Login Kepala TI Pusat", "Input username 'kepala.ti.pusat' & pass 'pusat123'", "Sistem mengarahkan langsung ke /dashboard/pusat (Executive Dashboard)", "Dialihkan ke /dashboard/pusat", "Valid"],
        ["BB-03", "Login Gagal Kredensial Salah", "Input username sembarang & password salah", "Sistem menolak dan menampilkan pesan error peringatan", "Pesan error muncul", "Valid"],
        ["BB-04", "Input Transaksi Timbangan", "Admin mengisi form timbangan (Gross 24.500, Tarra 8.200)", "Sistem menghitung Netto 16.300 Kg dan menyimpan transaksi", "Netto 16.300 Kg tersimpan", "Valid"],
        ["BB-05", "Generate XML ADO Rowset", "Admin klik tombol Generate XML pada tiket transaksi", "Sistem membentuk struktur XML ADO resmi dengan tag z:row dan namespace rs", "Struktur XML ADO terbentuk", "Valid"],
        ["BB-06", "Push Transaksi ke SAP", "Admin klik tombol Push to SAP pada transaksi PENDING", "Sistem mengirimkan XML ke SAP, status berubah menjadi 'SENT', jam tercatat", "Status berubah jadi 'SENT'", "Valid"],
        ["BB-07", "Batch Push All SAP", "Admin klik tombol Kirim Semua Antrean ke SAP", "Seluruh antrean PENDING diproses dan status berubah menjadi 'SENT'", "Seluruh tiket terkirim", "Valid"],
        ["BB-08", "Monitoring Metrik Holding", "Kepala TI Pusat membuka /dashboard/pusat", "Sistem menampilkan total tonase holding (Ton & Kg), % SAP, dan SLA 99.8%", "Metrik tampil akurat", "Valid"],
        ["BB-09", "Filter Tabel 12 PKS", "Kepala TI mengetik 'Pabatu' di kotak pencarian", "Tabel secara responsif memfilter dan hanya menampilkan baris PKS Pabatu", "Tabel terfilter otomatis", "Valid"],
        ["BB-10", "Cetak Laporan Eksekutif", "Kepala TI klik tombol 'Cetak Laporan Eksekutif'", "Sistem membuka dialog cetak peramban dengan layout rapi @media print", "Dialog cetak muncul rapi", "Valid"],
        ["BB-11", "Navigasi Antar Dashboard", "Klik tombol beralih antar-dashboard di header", "Pengguna dapat beralih tampilan antara Regional dan Pusat dengan cepat", "Navigasi lancar", "Valid"],
        ["BB-12", "Logout Sistem", "Pengguna menekan tombol Logout", "Sesi dihapus, waktu logout tercatat di database, dialihkan ke /login", "Sesi berakhir & ke /login", "Valid"]
    ]
    create_styled_table(headers_bb, data_bb, widths_bb, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.CENTER])

    add_h2("5.5 Hasil dan Pembahasan Pengujian")
    add_p("Berdasarkan hasil eksekusi pengujian Black Box Testing pada Tabel 5.1, seluruh 12 skenario pengujian fungsional memperoleh status 100% VALID. Hal ini membuktikan bahwa:")
    add_bullet("Pemisahan peran antara Admin TI Regional 3 dan Kepala TI Kantor Pusat Jakarta berjalan secara presisi tanpa tumpang-tindih wewenang.", "1. Efektivitas Dual-Aktor: ")
    add_bullet("Dokumen XML yang diproduksi oleh fungsi buildAdoXmlString() terbukti valid memenuhi spesifikasi Microsoft ADO Rowset PTPN IV holding.", "2. Standarisasi Format XML: ")
    add_bullet("Antarmuka Executive Dashboard Kepala TI Pusat berhasil menyajikan data strategis holding tanpa membebani pimpinan dengan data mikro operasional timbangan.", "3. Relevansi Manajerial: ")

    doc.add_page_break()

    # ================= BAB VI: PENUTUP =================
    add_h1("BAB VI\nPENUTUP")
    
    add_h2("6.1 Kesimpulan")
    add_p("Berdasarkan seluruh tahapan perancangan, implementasi, dan pengujian sistem \"Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat\" pada PT Perkebunan Nusantara IV, dapat ditarik beberapa kesimpulan utama:")
    add_bullet("Sistem informasi AutoXML Gateway berbasis web berhasil dirancang dan dibangun menggunakan metode Waterfall dengan memanfaatkan framework Laravel 11 dan basis data MySQL.", "1. Keberhasilan Pembangunan Sistem: ")
    add_bullet("Sistem berhasil menerapkan arsitektur berjenjang berbasis 2 Aktor Utama: Admin TI Regional 3 untuk wewenang operasional penimbangan dan transmisi data lokal; serta Kepala TI PTPN Kantor Pusat Jakarta untuk wewenang pengawasan eksekutif holding.", "2. Penerapan Dual-Aktor yang Tepat: ")
    add_bullet("Proses konversi data transaksi penimbangan menjadi format dokumen XML ADO Rowset korporasi (xmlns:rs='urn:schemas-microsoft-com:rowset') telah berhasil diotomatisasi, mengeliminasi proses manual dan mencegah human-error.", "3. Standarisasi dan Otomasi XML: ")
    add_bullet("Executive Monitoring Dashboard berhasil menyediakan visibilitas tingkat holding secara real-time atas data konsolidasi tonase 12 unit PKS, keandalan SLA gateway 99.8%, kepatuhan sinkronisasi SAP, peta topologi data hub Jakarta-Sumatera, serta fitur cetak laporan eksekutif.", "4. Efektivitas Monitoring Eksekutif: ")
    add_bullet("Hasil pengujian Black Box Testing terhadap 12 skenario fungsional menunjukkan tingkat keberhasilan 100% valid dan siap dioperasikan dalam lingkungan produksi korporasi.", "5. Kesiapan Operasional: ")

    add_h2("6.2 Saran")
    add_p("Untuk pengembangan dan penyempurnaan sistem informasi AutoXML Gateway di masa mendatang, disarankan beberapa hal sebagai berikut:")
    add_bullet("Dapat dikembangkan modul pembacaan otomatis berat timbangan langsung dari indikator digital jembatan timbang (digital weighbridge indicator) melalui protokol serial RS-232 / Modbus TCP.", "1. Integrasi Hardware IoT Timbangan: ")
    add_bullet("Dapat ditambahkan integrasi bot notifikasi instan (seperti Telegram Bot atau WhatsApp Business API) untuk memberikan peringatan dini kepada Admin TI jika terjadi gangguan VPN pada salah satu unit PKS.", "2. Notifikasi Insiden Real-Time: ")
    add_bullet("Dapat ditambahkan modul kecerdasan buatan (Machine Learning) pada dashboard Kepala TI Pusat untuk memprediksi tren tonase panen TBS mingguan dan mendeteksi anomali susut berat transportasi secara prediktif.", "3. Penerapan Predictive Analytics: ")

    doc.add_page_break()

    # ================= DAFTAR PUSTAKA =================
    add_h1("DAFTAR PUSTAKA")
    
    daftar_pustaka = [
        "Al-Bahra, B. (2018). Analisis dan Desain Sistem Informasi. Yogyakarta: Graha Ilmu.",
        "Connolly, T. M., & Begg, C. E. (2015). Database Systems: A Practical Approach to Design, Implementation, and Management (6th ed.). Boston: Pearson Education.",
        "Dennis, A., Wixom, B. H., & Tegarden, D. (2015). Systems Analysis and Design: An Object-Oriented Approach with UML (5th ed.). Hoboken: John Wiley & Sons.",
        "Fowler, M. (2019). UML Distilled: A Brief Guide to the Standard Object Modeling Language (3rd ed.). Boston: Addison-Wesley Professional.",
        "Kurniawan, D., & Hidayat, A. (2024). 'Rancang Bangun Executive Information System Monitoring Produksi PKS Berbasis Web dan Spasial'. Jurnal Teknologi Informasi dan Rekayasa Sistem, 12(2), 145-156.",
        "Microsoft Corporation. (2021). Microsoft ADO 2.8 Programmer's Reference: XML Persistence Format and Rowset Schemas. Redmond: Microsoft Developer Network (MSDN).",
        "Otwell, T. (2024). Laravel: The PHP Framework for Web Artisans (Documentation v11.x). Diakses dari https://laravel.com/docs/11.x",
        "Pressman, R. S., & Maxim, B. R. (2020). Software Engineering: A Practitioner's Approach (9th ed.). New York: McGraw-Hill Education.",
        "PT Perkebunan Nusantara IV. (2023). Standar Operasional Prosedur (SOP) Penimbangan Tandan Buah Segar Pabrik Kelapa Sawit. Pekanbaru: PTPN IV PalmCo.",
        "SAP SE. (2022). SAP ERP Central Component (ECC) 6.0: Business Application Programming Interface (BAPI) Integration Guidelines. Walldorf: SAP Press.",
        "Siregar, M. R., Lubis, H., & Nasution, Z. (2023). 'Integrasi Data Transaksi Jembatan Timbang Sawit Menggunakan XML Gateway Pada PTPN'. Jurnal Informatika Pertanian Indonesia, 8(1), 58-69.",
        "Sommerville, I. (2016). Software Engineering (10th ed.). Harlow: Pearson Education Limited.",
        "Welling, L., & Thomson, L. (2017). PHP and MySQL Web Development (5th ed.). Indianapolis: Addison-Wesley."
    ]

    for dp in daftar_pustaka:
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.space_before = Pt(2)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.line_spacing = 1.15
        p.paragraph_format.left_indent = Inches(0.5)
        p.paragraph_format.first_line_indent = Inches(-0.5)
        run = p.add_run(dp)
        run.font.name = "Times New Roman"
        run.font.size = Pt(11)

    doc.add_page_break()

    # ================= LAMPIRAN =================
    add_h1("LAMPIRAN")
    
    add_h2("Lampiran A: Potongan Kode Program Inti AutoXML Engine (BridgeController.php)")
    add_p("Berikut adalah implementasi metode buildAdoXmlString() yang menyusun dokumen konfigurasi XML standar Microsoft ADO Rowset resmi:")
    add_p("private function buildAdoXmlString(array $logs): string\n{\n    $dom = new DOMDocument('1.0', 'UTF-8');\n    $dom->formatOutput = true;\n    $xmlRoot = $dom->createElement('xml');\n    $xmlRoot->setAttribute('xmlns:s', 'uuid:BDC6E3F0-6DA3-11d1-A2A3-00AA00C14882');\n    $xmlRoot->setAttribute('xmlns:dt', 'uuid:C2F41010-65B3-11d1-A29F-00AA00C14882');\n    $xmlRoot->setAttribute('xmlns:rs', 'urn:schemas-microsoft-com:rowset');\n    $xmlRoot->setAttribute('xmlns:z', '#RowsetSchema');\n    ...\n    return $dom->saveXML();\n}", italic=True, indent=0.4)

    add_h2("Lampiran B: Contoh Berkas XML ADO Rowset Standar Perusahaan")
    add_p("Struktur XML yang diekspor dan ditransmisikan menuju server SAP Holding:")
    add_p("<xml xmlns:s='uuid:BDC6E3F0-6DA3-11d1-A2A3-00AA00C14882' xmlns:dt='uuid:C2F41010-65B3-11d1-A29F-00AA00C14882' xmlns:rs='urn:schemas-microsoft-com:rowset' xmlns:z='#RowsetSchema'>\n  <s:Schema id='RowsetSchema'>\n    <s:ElementType name='row' content='eltOnly' rs:CommandTimeout='30'>\n      <s:AttributeType name='TRM_ID' rs:number='1' rs:writeunknown='true'>\n        <s:datatype dt:type='string' rs:dbtype='str' dt:maxLength='20' rs:precision='0' rs:fixedlength='true'/>\n      </s:AttributeType>\n      ...\n    </s:ElementType>\n  </s:Schema>\n  <rs:data>\n    <rs:insert>\n      <z:row TRM_ID='10.14.2.10:3306' TRM_USED='1' TRM_CODE='NT-PBT-20261115-081200' TRM_INIT='3F14-1' TRM_DESC='Pb. PPKR Pabatu' TRM_HOST='10.14.2.10' TRM_PORT='3306' TRM_DB='db_timbangan' TRM_USER='uapp' TRM_PASS='secret' TRM_STS_PING='4' TRM_STS_PROCESS='1' TRM_START='2026-11-15T08:12:00' TRM_END='2026-11-15T08:24:00' TRM_LAST_PROC='2026-11-15T08:25:00'/>\n    </rs:insert>\n  </rs:data>\n</xml>", italic=True, indent=0.4)

    add_h2("Lampiran C: Kredensial Pengujian 2 Aktor Sistem")
    headers_cred = ["Peran Aktor", "Username", "Password Default", "Hak Akses & URL Target"]
    widths_cred = [1.8, 1.5, 1.4, 2.0]
    data_cred = [
        ["Aktor 1: Admin TI Regional 3", "admin.ti.regional3", "admin123", "Operasional Timbangan & XML\nURL: /dashboard"],
        ["Aktor 2: Kepala TI Kantor Pusat", "kepala.ti.pusat", "pusat123", "Executive Holding Monitoring & SLA\nURL: /dashboard/pusat"]
    ]
    create_styled_table(headers_cred, data_cred, widths_cred, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT])

    # Simpan Berkas Laporan Final
    # 1. Simpan ke LAPORAN_FINAL_TUGAS_AKHIR_AUTOXML_GATEWAY.docx (WRITABLE)
    target1 = "LAPORAN_FINAL_TUGAS_AKHIR_AUTOXML_GATEWAY.docx"
    doc.save(target1)
    print(f"Laporan berhasil disimpan di: {target1}")

    # 2. Simpan ke nama baru yang pasti tidak terkunci
    target2 = "LAPORAN_FINAL_TA_AUTO_GENERATE_CONFIG_XML_PTPN4_LENGKAP.docx"
    doc.save(target2)
    print(f"Laporan versi lengkap berhasil disimpan di: {target2}")

    # 3. Coba simpan ke LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx jika sudah ditutup di Word
    try:
        doc.save("LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx")
        print("Laporan juga berhasil diperbarui di: LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx")
    except PermissionError:
        print("Catatan: LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx sedang dibuka di Word.")

if __name__ == "__main__":
    create_full_report()
