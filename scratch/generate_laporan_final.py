# -*- coding: utf-8 -*-
"""
Script Generator Laporan Final Tugas Akhir Lengkap (.docx)
Judul Resmi: AUTO GENERATE CONFIG XML PADA TIMBANGAN PTPN 4 REGIONAL 3 UNTUK MELEMPAR DATA KE SISTEM SAP KANTOR PUSAT
Penulis: Muhammad Nur Almuttakin
Aktor: 2 Aktor Sistem (Admin TI Regional 3 & Kepala TI PTPN Kantor Pusat Jakarta)
"""

import os
import sys
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

def create_full_report():
    doc = docx.Document()

    # Pengaturan Margin Halaman (Standar Skripsi/Tugas Akhir: Kiri 4cm, Atas 3cm, Kanan 3cm, Bawah 3cm)
    for section in doc.sections:
        section.top_margin = Inches(1.18)      # 3.0 cm
        section.bottom_margin = Inches(1.18)   # 3.0 cm
        section.left_margin = Inches(1.57)     # 4.0 cm
        section.right_margin = Inches(1.18)    # 3.0 cm

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
        run.font.color.rgb = RGBColor(15, 81, 50) # Forest Green
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
    add_bullet("Bagaimana merancang dan membangun sistem AutoXML Gateway yang mampu menghitung nilai netto secara otomatis dan mengonversi data penimbangan TBS dari 12 unit PKS ke format XML ADO Rowset standar perusahaan?", "1. ")
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
    add_bullet("Menguraikan analisis sistem berjalan, analisis kebutuhan fungsional & non-fungsional, perancangan Use Case Diagram HANYA DENGAN 2 AKTOR (Admin TI Regional 3 dan Kepala TI Kantor Pusat Jakarta), skenario use case, activity diagram, sequence diagram, class diagram, component diagram, ERD database, dan rancangan wireframe antarmuka.", "BAB IV ANALISIS DAN PERANCANGAN SISTEM: ")
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
    add_p("Pengembangan perangkat lunak menerapkan System Development Life Cycle (SDLC) model Waterfall (Classic Life Cycle) menurut Ian Sommerville dan Roger S. Pressman. Model ini dipilih karena karakteristik kebutuhan sistem integrasi enterprise ini memiliki spesifikasi teknis yang pasti, berurutan, dan menuntut standarisasi ketat:")
    add_bullet("Tahap inisiasi proyek melalui dialog dengan pemangku kepentingan untuk mengidentifikasi domain permasalahan, batasan 2 aktor sistem, dan spesifikasi format XML ADO Rowset yang disepakati.", "1. Communication (Analisis Kebutuhan): ")
    add_bullet("Menyusun estimasi penjadwalan kerja, pemetaan 12 unit PKS, alokasi infrastruktur IP VPN, dan manajemen risiko keterlambatan data.", "2. Planning (Perencanaan): ")
    add_bullet("Menerjemahkan kebutuhan bisnis ke dalam diagram pemodelan berorientasi objek (Use Case 2 Aktor, Skenario, Activity, Sequence, Class, Component Diagram), skema ERD database, dan rancangan wireframe antarmuka.", "3. Modeling (Perancangan): ")
    add_bullet("Mengimplementasikan rancangan ke dalam kode program web Laravel 11, pembuatan pustaka pembangun XML DOMDocument, pembuatan seeder 2 aktor, serta pengujian Black Box Testing.", "4. Construction (Pengodean & Pengujian): ")
    add_bullet("Memasang aplikasi pada server gateway lokal (Laragon/Debian VM), evaluasi umpan balik pengguna, dan serah terima sistem kepada penguji/penilai sidang tugas akhir.", "5. Deployment & Maintenance: ")

    add_h2("3.4 Analisis Kebutuhan Sistem")
    add_p("Untuk menjamin kelancaran operasional sistem AutoXML Gateway, dirumuskan spesifikasi minimum perangkat keras dan perangkat lunak yang dibutuhkan:")
    
    headers_hw = ["Kategori Komponen", "Spesifikasi Perangkat Keras Minimum", "Rekomendasi Lingkungan Produksi"]
    widths_hw = [1.8, 2.3, 2.4]
    data_hw = [
        ["Processor (CPU)", "Intel Core i3 Gen 8 / AMD Ryzen 3 (Quad Core 2.4 GHz)", "Intel Core i7 / Xeon E-Series (8 Cores 3.2 GHz)"],
        ["Memori (RAM)", "4 GB DDR4", "16 GB DDR4 ECC RAM"],
        ["Penyimpanan (Storage)", "SSD 128 GB SATA III", "SSD NVMe 512 GB Enterprise (RAID 1)"],
        ["Kartu Jaringan (NIC)", "Gigabit Ethernet Controller 100/1000 Mbps", "Dual Redundant Gigabit Ethernet Controller"],
        ["Konektivitas Jaringan", "Koneksi VPN / IP Intranet Dedicated 10 Mbps", "Leased Line Dedicated IP Intranet 100 Mbps (SLA 99.8%)"],
        ["Monitor & Tampilan", "Resolusi layar 1366 x 768 piksel", "Full HD 1920 x 1080 piksel (Dual Monitor Eksekutif)"]
    ]
    create_styled_table(headers_hw, data_hw, widths_hw, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT])

    headers_sw = ["Komponen Perangkat Lunak", "Spesifikasi yang Digunakan", "Fungsi / Peranan dalam Sistem"]
    widths_sw = [2.0, 2.0, 2.5]
    data_sw = [
        ["Sistem Operasi", "Windows 10/11 Pro 64-bit / Linux Debian 12", "Lingkungan eksekusi host dan gateway transmisi data"],
        ["Web Server", "Apache 2.4 / Nginx Engine", "Melayani permintaan HTTP/HTTPS antarmuka web"],
        ["Bahasa Pemrograman", "PHP versi 8.2+ 64-bit", "Pemrosesan logika bisnis, controller, dan engine XML"],
        ["Database Server", "MySQL Community Server 8.0+", "Menyimpan data transaksi, master PKS, dan riwayat login"],
        ["Framework Web", "Laravel Framework 11.x", "Penyedia struktur MVC, ORM Eloquent, dan keamanan sesi"],
        ["Pustaka Pemetaan", "Leaflet JS versi 1.9.4", "Merender peta spasial topologi 12 PKS dan hub Jakarta"],
        ["Pustaka Visualisasi", "Chart.js versi 4.x", "Merender visualisasi tonase dan komposisi status SAP"],
        ["Web Browser", "Google Chrome 120+ / MS Edge", "Aplikasi peramban pengguna untuk mengakses sistem"]
    ]
    create_styled_table(headers_sw, data_sw, widths_sw, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT])

    doc.add_page_break()

    # ================= BAB IV: ANALISIS DAN PERANCANGAN SISTEM =================
    add_h1("BAB IV\nANALISIS DAN PERANCANGAN SISTEM")
    
    add_h2("4.1 Analisis Sistem Berjalan")
    add_p("Pada sistem berjalan, operator jembatan timbang di unit PKS mencatat bobot bruto saat truk tiba dan bobot tarra saat truk keluar. Data tersebut tersimpan pada komputer lokal di pos timbangan. Petugas administrasi lokal setiap sore hari melakukan ekspor berkas data ke dalam format berkas spreadsheet (.xls) dan mengirimkannya melalui surat elektronik (email) atau aplikasi perpesanan instan menuju tim TI kantor regional di Pekanbaru. Tim regional kemudian mengumpulkan berkas-berkas dari 12 unit PKS, melakukan validasi manual, lalu mengunggahnya ke portal data SAP.")
    add_p("Kelemahan nyata dari alur sistem berjalan ini adalah:")
    add_bullet("Keterlambatan konsolidasi tonase mencapai 12 hingga 24 jam setelah penimbangan berlangsung.", "1. Tidak Real-Time: ")
    add_bullet("Tingginya potensi salah ketik angka berat timbangan pada proses pemindahan berkas manual.", "2. Rawan Human Error: ")
    add_bullet("Kepala Divisi TI di Jakarta tidak memiliki akses pemantauan seketika atas kendala jaringan atau ketertunggakan pengiriman data dari unit pabrik.", "3. Minimnya Pengawasan Holding: ")

    add_h2("4.2 Analisis Kebutuhan Sistem")
    add_h3("4.2.1 Analisis Kebutuhan Fungsional (Functional Requirements)")
    add_p("Kebutuhan fungsional sistem dirancang secara ketat berdasarkan hak akses 2 Aktor Utama Sistem:")
    
    headers_fn = ["ID Kebutuhan", "Deskripsi Kebutuhan Fungsional", "Aktor Berwenang"]
    widths_fn = [1.2, 3.8, 1.5]
    data_fn = [
        ["SKF-01", "Sistem menyediakan autentikasi pengguna dengan verifikasi username dan password serta pencatatan audit riwayat login di database.", "Admin TI Regional 3 & Kepala TI Kantor Pusat"],
        ["SKF-02", "Sistem mengarahkan pengguna secara otomatis ke tampilan antarmuka yang sesuai dengan perannya pasca-login (Role-based Redirection).", "Sistem (Otomatis)"],
        ["SKF-03", "Sistem menyediakan form pencatatan transaksi penimbangan TBS (Plat nomor, PKS, jam masuk, jam keluar, Gross, Tarra) dan menghitung nilai Netto secara otomatis.", "Admin TI Regional 3"],
        ["SKF-04", "Sistem menyediakan tombol Generate XML untuk mengonversi data transaksi menjadi dokumen XML ADO Rowset berstandar korporasi.", "Admin TI Regional 3"],
        ["SKF-05", "Sistem menyediakan fitur Push to SAP untuk mengirimkan payload XML data transaksi ke server SAP holding, baik secara satuan maupun massal (Batch Push All).", "Admin TI Regional 3"],
        ["SKF-06", "Sistem menampilkan peta interaktif GIS pemantauan status koneksi VPN dan kondisi 12 unit PKS Regional III.", "Admin TI Regional 3"],
        ["SKF-07", "Sistem menampilkan kartu metrik eksekutif konsolidasi tonase total (Ton & Kg), persentase sinkronisasi SAP, dan SLA uptime gateway holding.", "Kepala TI Kantor Pusat"],
        ["SKF-08", "Sistem menampilkan grafik batang distribusi tonase TBS 12 unit PKS dan grafik donat status kepatuhan SAP holding.", "Kepala TI Kantor Pusat"],
        ["SKF-09", "Sistem menyajikan visualisasi topologi jaringan data hub yang menghubungkan Regional III (Pekanbaru) menuju Data Center Jakarta.", "Kepala TI Kantor Pusat"],
        ["SKF-10", "Sistem menyajikan tabel matriks konsolidasi kinerja 12 PKS terpantau beserta fitur pencarian cepat tanpa formulir input penimbangan.", "Kepala TI Kantor Pusat"],
        ["SKF-11", "Sistem menyediakan tabel audit trail transaksi terverifikasi SAP holding dan tombol cetak laporan eksekutif (Print Layout).", "Kepala TI Kantor Pusat"],
        ["SKF-12", "Sistem menyediakan fitur logout yang secara otomatis memperbarui waktu sesi berakhir pada tabel riwayat aktivitas pengguna.", "Admin TI Regional 3 & Kepala TI Kantor Pusat"]
    ]
    create_styled_table(headers_fn, data_fn, widths_fn, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.LEFT])

    add_h3("4.2.2 Analisis Kebutuhan Non-Fungsional (Non-Functional Requirements)")
    add_p("Kebutuhan non-fungsional ditinjau menggunakan model FURPS (Functionality, Usability, Reliability, Performance, Supportability):")
    add_bullet("Antarmuka dirancang responsif, bersih, berorientasi korporat PTPN dengan palet warna Forest Green dan Gold, serta navigasi yang intuitif.", "1. Usability (Kemudahan Penggunaan): ")
    add_bullet("Sistem menjamin Service Level Agreement (SLA) ketersediaan transmisi gateway hingga 99.8% dengan penanganan kegagalan koneksi secara anggun (graceful degradation).", "2. Reliability (Keandalan): ")
    add_bullet("Waktu generasi berkas XML ADO Rowset di bawah 150 milidetik per 10 transaksi, serta waktu rendering dashboard eksekutif di bawah 1 detik.", "3. Performance (Kinerja): ")
    add_bullet("Dilengkapi proteksi CSRF token pada setiap formulir POST, hashing password berbasis Bcrypt standar industri, dan pencatatan IP address pada tabel audit login.", "4. Security (Keamanan): ")

    add_h2("4.3 Perancangan UML (Unified Modeling Language)")
    add_h3("4.3.1 Use Case Diagram (Ditegaskan HANYA 2 Aktor)")
    add_p("Berbeda dengan pemodelan konvensional yang memecah operator lokal dan entitas mesin eksternal, Use Case Diagram pada sistem ini DITEGASKAN HANYA MEMILIKI 2 AKTOR MANUSIA SESUAI PERSYARATAN PENGUJIAN TUGAS AKHIR, yaitu:")
    add_bullet("Aktor yang bertanggung jawab atas seluruh operasional teknis jembatan timbang, pembuatan XML ADO Rowset, eksekusi transmisi SAP regional, dan monitoring 12 unit PKS.", "Aktor 1: Admin TI Regional 3 - ")
    add_bullet("Aktor tingkat pimpinan holding yang bertindak sebagai pemantau eksekutif, bertugas meninjau metrik konsolidasi tonase, memantau SLA uptime jaringan data center, mengevaluasi rasio keberhasilan integrasi SAP, dan mencetak laporan eksekutif holding tanpa terlibat dalam pengisian tiket timbangan.", "Aktor 2: Kepala TI PTPN Kantor Pusat (Jakarta) - ")

    # Tabel Use Case Mapping
    headers_uc_map = ["Aktor Sistem", "Daftar Use Case yang Dimiliki", "Karakteristik & Wewenang Hak Akses"]
    widths_uc_map = [1.8, 2.5, 2.2]
    data_uc_map = [
        ["Admin TI Regional 3\n(admin.ti.regional3)", "1. Melakukan Login Akun\n2. Menginput Transaksi Timbangan\n3. Menghitung Netto Otomatis\n4. Men-generate XML ADO Rowset\n5. Melakukan Push Data ke SAP (Single/All)\n6. Memantau Status Jaringan 12 PKS\n7. Meninjau Riwayat Aktivitas Sesi\n8. Melakukan Logout Sesi", "Tingkat Operasional Regional:\nMemiliki kontrol manipulasi data penimbangan lokal, konversi berkas XML, dan eksekusi push transmisi data ke SAP."],
        ["Kepala TI Kantor Pusat (Jakarta)\n(kepala.ti.pusat)", "1. Melakukan Login Akun\n2. Memantau Metrik Konsolidasi Tonase Holding\n3. Memantau Rasio Keberhasilan Integrasi SAP\n4. Memantau SLA Ketersediaan Jalur Gateway\n5. Melihat Topologi Data Hub Jakarta-Sumatera\n6. Meninjau Matriks Kinerja 12 Unit PKS\n7. Memeriksa Audit Ledger Verifikasi SAP\n8. Mencetak Dokumen Laporan Eksekutif\n9. Melakukan Logout Sesi", "Tingkat Eksekutif Holding:\nMemiliki kontrol pemantauan menyeluruh tingkat nasional/holding, tanpa hak akses input timbangan, berorientasi KPI strategis."]
    ]
    create_styled_table(headers_uc_map, data_uc_map, widths_uc_map, [WD_ALIGN_PARAGRAPH.CENTER, WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.3.2 Skenario Use Case Lengkap untuk Kedua Aktor")
    add_p("Berikut adalah rincian skenario alur kerja use case inti untuk kedua aktor sistem:")

    # Skenario 1: Login
    add_p("Tabel 4.1 Skenario Use Case 1: Autentikasi Pengguna & Redirection", bold_prefix="")
    headers_sc1 = ["Atribut Skenario", "Deskripsi Rincian"]
    widths_sc1 = [1.8, 4.7]
    data_sc1 = [
        ["Nama Use Case", "Autentikasi Pengguna & Dynamic Redirection (UC-01)"],
        ["Aktor", "Admin TI Regional 3, Kepala TI PTPN Kantor Pusat Jakarta"],
        ["Deskripsi", "Memverifikasi kredensial pengguna dan mengarahkan ke dashboard yang relevan sesuai peran yang dimiliki."],
        ["Kondisi Awal (Pre-Condition)", "Pengguna membuka peramban web dan mengakses URL login (/login)."],
        ["Kondisi Akhir (Post-Condition)", "Sesi aktif tersimpan di server dan riwayat login tercatat pada tabel riwayat_logins."],
        ["Alur Utama (Main Flow)", "1. Pengguna memasukkan username dan password pada form login.\n2. Pengguna menekan tombol Masuk ke Dashboard.\n3. Sistem memvalidasi kredensial pengguna ke tabel users menggunakan Hash Bcrypt.\n4. Sistem mencatat waktu login, alamat IP, dan User-Agent ke tabel riwayat_logins.\n5. Jika peran adalah 'Admin Regional', sistem mengarahkan ke /dashboard (Dashboard Regional 3).\n6. Jika peran adalah 'Kepala TI Pusat', sistem mengarahkan ke /dashboard/pusat (Executive Holding Dashboard)."],
        ["Alur Alternatif", "Jika username atau password tidak cocok, sistem menampilkan pesan peringatan error autentikasi dan tetap berada pada form login."]
    ]
    create_styled_table(headers_sc1, data_sc1, widths_sc1, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 2: Input & Generate XML (Admin Regional)
    add_p("Tabel 4.2 Skenario Use Case 2: Input Transaksi & Generate XML ADO (Khusus Admin Regional)", bold_prefix="")
    data_sc2 = [
        ["Nama Use Case", "Input Transaksi Penimbangan & Auto Generate ADO XML (UC-02)"],
        ["Aktor", "Admin TI Regional 3"],
        ["Deskripsi", "Mencatat transaksi timbangan TBS pabrik dan menghasilkan berkas XML ADO Rowset resmi."],
        ["Kondisi Awal (Pre-Condition)", "Admin TI Regional 3 telah berhasil login dan berada pada menu Transaksi & XML Engine."],
        ["Kondisi Akhir (Post-Condition)", "Data transaksi tersimpan di database dengan status XML 'Generated' dan Netto terhitung."],
        ["Alur Utama (Main Flow)", "1. Admin mengisi nomor plat kendaraan, memilih lokasi dari 12 unit PKS, jam masuk/keluar, bobot Gross, dan bobot Tarra.\n2. Admin menekan tombol Simpan & Generate XML.\n3. Sistem secara otomatis menghitung Netto = Gross - Tarra.\n4. Sistem membentuk dokumen XML ADO Recordset dengan skema s:Schema, rs:rowbase, dan elemen z:row sesuai standar korporasi.\n5. Sistem menyimpan berkas XML ke storage dan memperbarui tabel transaksi_timbangans.\n6. Tampilan antarmuka menampilkan notifikasi sukses dan preview XML stream interaktif."],
        ["Alur Alternatif", "Jika nilai Tarra melebihi Gross, sistem menampilkan validasi peringatan nilai tidak valid dan meminta koreksi input."]
    ]
    create_styled_table(headers_sc1, data_sc2, widths_sc1, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 3: Push to SAP (Admin Regional)
    add_p("Tabel 4.3 Skenario Use Case 3: Push Data Transaksi ke SAP ERP (Khusus Admin Regional)", bold_prefix="")
    data_sc3 = [
        ["Nama Use Case", "Transmisi Payload XML ke SAP ERP Server (UC-03)"],
        ["Aktor", "Admin TI Regional 3"],
        ["Deskripsi", "Mengirimkan dokumen XML transaksi yang telah tervalidasi menuju server SAP ERP holding."],
        ["Kondisi Awal (Pre-Condition)", "Transaksi timbangan telah berstatus XML 'Generated' dan berstatus SAP 'Pending'."],
        ["Kondisi Akhir (Post-Condition)", "Status transaksi pada database diperbarui menjadi 'SENT' dan tercatat pada log SAP."],
        ["Alur Utama (Main Flow)", "1. Admin memilih baris transaksi berstatus PENDING atau menekan tombol 'Kirim Semua ke SAP'.\n2. Sistem membaca payload XML ADO Rowset dari storage/database.\n3. Sistem mengirimkan payload melalui koneksi private IP VPN menuju endpoint SAP BAPI.\n4. Server SAP merespons dengan status 200 OK dan nomor referensi verifikasi.\n5. Sistem memperbarui status_sap menjadi 'SENT' dan mengisi waktu sap_sent_at.\n6. Sistem mencatat aktivitas ke tabel log_integrasi_saps."],
        ["Alur Alternatif", "Jika koneksi VPN terputus, sistem mempertahankan status 'Pending' dan menampilkan peringatan kegagalan transmisi."]
    ]
    create_styled_table(headers_sc1, data_sc3, widths_sc1, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 4: Monitoring Holding (Kepala TI Pusat)
    add_p("Tabel 4.4 Skenario Use Case 4: Monitoring Konsolidasi & SLA Holding (Khusus Kepala TI Pusat)", bold_prefix="")
    data_sc4 = [
        ["Nama Use Case", "Executive Holding Monitoring & SLA Tracking (UC-04)"],
        ["Aktor", "Kepala TI PTPN Kantor Pusat (Jakarta)"],
        ["Deskripsi", "Memantau data makro konsolidasi tonase, keandalan SLA gateway, dan status integrasi SAP 12 PKS."],
        ["Kondisi Awal (Pre-Condition)", "Kepala TI Kantor Pusat telah login dan berada pada URL /dashboard/pusat."],
        ["Kondisi Akhir (Post-Condition)", "Kepala TI memperoleh visualisasi holistik kinerja operasional timbangan tanpa form input."],
        ["Alur Utama (Main Flow)", "1. Kepala TI membuka Executive Monitoring Dashboard.\n2. Sistem menghitung agregasi total Netto TBS (Ton), persentase sinkronisasi SAP, dan SLA uptime gateway.\n3. Sistem merender grafik distribusi tonase 12 unit PKS dan proporsi status SAP.\n4. Sistem memuat peta topologi interaktif Leaflet data hub Pekanbaru ke Jakarta DC.\n5. Kepala TI meninjau matriks tabel 12 PKS dan dapat melakukan filter pencarian nama pabrik.\n6. Kepala TI meninjau tabel audit trail 15 transaksi terakhir yang terverifikasi SAP holding."],
        ["Alur Alternatif", "Kepala TI dapat menekan tombol 'Beralih ke Dashboard Admin Regional 3' jika memerlukan verifikasi teknis mendalam."]
    ]
    create_styled_table(headers_sc1, data_sc4, widths_sc1, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    # Skenario 5: Cetak Laporan Eksekutif (Kepala TI Pusat)
    add_p("Tabel 4.5 Skenario Use Case 5: Cetak Laporan Konsolidasi Eksekutif (Khusus Kepala TI Pusat)", bold_prefix="")
    data_sc5 = [
        ["Nama Use Case", "Cetak Laporan Konsolidasi Eksekutif Holding (UC-05)"],
        ["Aktor", "Kepala TI PTPN Kantor Pusat (Jakarta)"],
        ["Deskripsi", "Menghasilkan format cetak resmi dari ringkasan kinerja konsolidasi timbangan regional."],
        ["Kondisi Awal (Pre-Condition)", "Kepala TI Pusat berada pada Executive Dashboard."],
        ["Kondisi Akhir (Post-Condition)", "Dokumen laporan cetak rapi siap diunduh sebagai PDF atau dicetak fisik."],
        ["Alur Utama (Main Flow)", "1. Kepala TI menekan tombol 'Cetak Laporan Eksekutif'.\n2. Sistem memicu fungsi window.print() dengan menerapkan stylesheet media print khusus (@media print).\n3. Elemen-elemen non-cetak (tombol navigasi, header animasi, peta dinamis) disembunyikan otomatis.\n4. Tabel matriks 12 PKS dan kartu KPI dikonversi menjadi layout dokumen resmi hitam-putih/hijau berstandar holding.\n5. Kepala TI mengonfirmasi pencetakan dokumen fisik atau ekspor berkas PDF."],
        ["Alur Alternatif", "Jika dibatalkan oleh pengguna, peramban kembali ke tampilan dashboard tanpa perubahan."]
    ]
    create_styled_table(headers_sc1, data_sc5, widths_sc1, [WD_ALIGN_PARAGRAPH.LEFT, WD_ALIGN_PARAGRAPH.JUSTIFY])

    add_h3("4.3.3 Activity Diagram")
    add_p("Activity diagram memodelkan alir aktivitas sistem secara sekuensial. Sistem ini memiliki 2 jalur aktivitas utama berdasarkan aktor:")
    add_bullet("Alur aktivitas mencakup: Login -> Akses Modul Operasional -> Isi Form Timbangan -> Klik Simpan -> Sistem Hitung Netto -> Sistem Bentuk ADO XML -> Tampilkan Preview -> Klik Push SAP -> Sistem Kirim Payload via VPN -> Validasi Status SENT -> Perbarui Audit Log.", "1. Aktivitas Admin TI Regional 3: ")
    add_bullet("Alur aktivitas mencakup: Login -> Redirection ke Executive Dashboard -> Sistem Agregasi Metrik 12 PKS -> Sistem Render Peta Data Hub & Grafik -> Kepala TI Pantau SLA & Tonase -> Filter Matriks Pabrik -> Tinjau Audit Ledger SAP -> Klik Cetak Laporan Eksekutif -> Cetak Dokumen Rekapitulasi.", "2. Aktivitas Kepala TI Kantor Pusat Jakarta: ")

    add_h3("4.3.4 Sequence Diagram")
    add_p("Sequence diagram menggambarkan interaksi objek sepanjang waktu. Pada proses transmisi data (Push SAP), urutan interaksi objek adalah:")
    add_bullet("Admin TI Regional 3 menekan tombol Push SAP pada Blade View.", "1. ")
    add_bullet("Blade View mengirimkan permintaan HTTP POST menuju route bridge.push di BridgeController.", "2. ")
    add_bullet("BridgeController mengambil data transaksi dari model TransaksiTimbangan.", "3. ")
    add_bullet("BridgeController memanggil metode privat buildAdoXmlString() untuk menyusun payload XML standar Microsoft ADO Rowset.", "4. ")
    add_bullet("BridgeController mengirimkan payload XML melalui gateway komunikasi ke Server SAP.", "5. ")
    add_bullet("Server SAP mengembalikan status HTTP 200 OK beserta nomor dokumen referensi SAP.", "6. ")
    add_bullet("BridgeController memperbarui status transaksi menjadi 'SENT' dan mencatat riwayat ke model LogIntegrasiSap.", "7. ")
    add_bullet("Sistem mengembalikan respons sukses ke antarmuka pengguna disertai pembaruan metrik pada Executive Dashboard Kepala TI Pusat.", "8. ")

    add_h3("4.3.5 Class Diagram")
    add_p("Struktur kelas sistem dimodelkan dengan memisahkan representasi entitas data (Models) dan pengontrol alur bisnis (Controllers). Kelas-kelas utama meliputi:")
    add_bullet("Menangani autentikasi login, regenerasi token sesi, pengecekan peran (role), pencatatan sesi aktif, dan logout.", "1. AuthController: ")
    add_bullet("Menangani pemuatan dashboard regional, dashboard eksekutif pusat (pusatDashboard), komputasi netto (store), penghapusan transaksi (destroy), pembuatan XML ADO (buildAdoXmlString), dan transmisi SAP (pushSap, pushAllSap).", "2. BridgeController: ")
    add_bullet("Entitas pengguna sistem yang memuat atribut: id, name, username, email, password, role ('Admin Regional' atau 'Kepala TI Pusat').", "3. User: ")
    add_bullet("Entitas transaksi penimbangan yang memuat atribut: id, unit_pks_id, nota_no, no_plat, pks_loc, jam_masuk, jam_keluar, gross, tarra, netto, vpn_ip, status_vpn, xml_status, status_sap, sap_sent_at.", "4. TransaksiTimbangan: ")
    add_bullet("Entitas data master 12 pabrik: id, kode_unit, nama_unit, lokasi, ip_vpn, status_vpn, status_wifi, kapasitas_olah_tph, latitude, longitude.", "5. UnitPks: ")
    add_bullet("Entitas audit jejak rekam pengiriman ke SAP: id, transaksi_id, pks_origin, sap_ref_no, status_transmisi, waktu_kirim, response_payload.", "6. LogIntegrasiSap: ")
    add_bullet("Entitas riwayat login: id, user_id, nama_pengguna, username, role, ip_address, user_agent, login_at, logout_at, status.", "7. RiwayatLogin: ")

    add_h3("4.3.6 Component Diagram")
    add_p("Component diagram menggambarkan komponen fisik perangkat lunak dalam arsitektur aplikasi:")
    add_bullet("Browser Klien (Google Chrome / Edge) pada komputer kerja Admin Regional 3 dan Kepala TI Pusat.", "1. User Interface Component: ")
    add_bullet("Routing Engine, Middleware Auth, AuthController, dan BridgeController di bawah Laravel 11.", "2. Application Controller Component: ")
    add_bullet("Fungsi buildAdoXmlString() yang memanfaatkan ekstensi PHP DOMDocument untuk menyusun skema ADO Recordset.", "3. XML Generator Engine Component: ")
    add_bullet("Database MySQL db_timbangan yang diakses melalui Eloquent ORM.", "4. Data Persistence Component: ")
    add_bullet("Server SAP ERP Holding di Jakarta dan Gateway Debian Linux VM di Pekanbaru.", "5. External Infrastructure Component: ")

    add_h2("4.4 Perancangan Basis Data (Entity Relationship Diagram)")
    add_p("Basis data sistem dirancang dengan nama db_timbangan. Relasi antar-tabel dimodelkan sebagai berikut:")
    add_bullet("Satu unit PKS (UnitPks) dapat memiliki banyak transaksi penimbangan (TransaksiTimbangan) [Relasi 1 to Many].", "1. ")
    add_bullet("Satu transaksi timbangan (TransaksiTimbangan) dapat memiliki banyak riwayat log pengiriman (LogIntegrasiSap) [Relasi 1 to Many].", "2. ")
    add_bullet("Satu pengguna (User) dapat memiliki banyak rekaman riwayat sesi (RiwayatLogin) [Relasi 1 to Many].", "3. ")

    # Struktur Tabel Users
    add_p("Tabel 4.6 Struktur Tabel Pengguna (users)", bold_prefix="")
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
    add_p("Tabel 4.7 Struktur Tabel Transaksi Timbangan (transaksi_timbangans)", bold_prefix="")
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

    add_h2("4.5 Perancangan Antarmuka Pengguna (Wireframe & Layout)")
    add_p("Perancangan antarmuka memisahkan secara tegas pengalaman visual kedua aktor:")
    add_bullet("Menampilkan kartu formulir login terpusat, logo resmi PTPN IV PalmCo, indikator sistem terintegrasi, serta tombol bantuan pengisian otomatis kredensial untuk mempermudah demonstrasi pengujian 2 aktor.", "1. Rancangan Halaman Login (/login): ")
    add_bullet("Menampilkan sidebar menu navigasi hijau tua (Forest Green), kartu ringkasan antrean XML, tabel operasional data timbangan, modal input timbangan baru, widget generator XML interaktif, peta Leaflet 12 PKS, tabel riwayat sesi login, dan tombol beralih cepat ke Dashboard Pusat.", "2. Rancangan Dashboard Admin TI Regional 3 (/dashboard): ")
    add_bullet("Menampilkan header eksekutif holding dengan badge peran khusus, 4 kartu KPI holding (Tonase Bersih Terkonsolidasi dalam Ton & Kg, Persentase Sinkronisasi SAP, SLA Jaringan Gateway 99.8%, dan Kesiapan 12 Node PKS), grafik batang distribusi tonase per pabrik, grafik donat status SAP, peta topologi data hub Jakarta-Pekanbaru, tabel matriks 12 PKS tanpa form input manual, log audit verifikasi holding, serta tombol cetak laporan eksekutif resmi.", "3. Rancangan Executive Monitoring Dashboard (/dashboard/pusat): ")

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

    # Simpan Berkas Laporan Final sesuai Judul Resmi
    output_filename = "LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx"
    doc.save(output_filename)
    print(f"Laporan Final Tugas Akhir berhasil dibuat: {output_filename}")

    # Juga update LAPORAN_FINAL_TUGAS_AKHIR_AUTOXML_GATEWAY.docx jika tidak sedang dikunci Word
    try:
        doc.save("LAPORAN_FINAL_TUGAS_AKHIR_AUTOXML_GATEWAY.docx")
        print("File LAPORAN_FINAL_TUGAS_AKHIR_AUTOXML_GATEWAY.docx juga berhasil diperbarui.")
    except PermissionError:
        print("Catatan: File lama sedang dibuka di Microsoft Word, pembaruan telah disimpan di: LAPORAN_FINAL_AUTO_GENERATE_CONFIG_XML_PTPN4.docx")

if __name__ == "__main__":
    create_full_report()
