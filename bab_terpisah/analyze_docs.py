"""Analyze docx files - full output to file."""
import re
from docx import Document

def analyze_doc(filepath, label, output_file):
    output_file.write(f"\n{'='*80}\n")
    output_file.write(f"ANALYZING: {label}\n")
    output_file.write(f"{'='*80}\n")
    
    doc = Document(filepath)
    
    for i, para in enumerate(doc.paragraphs):
        text = para.text.strip()
        if text:
            style = para.style.name if para.style else "None"
            output_file.write(f"[{i:04d}] [{style}] {text[:300]}\n")
            
            # Find citations
            citations = re.findall(r'\([^)]*\d{4}[^)]*\)', text)
            if citations:
                output_file.write(f"       CITATIONS: {citations}\n")
            
            # Check for runs with italic
            for run in para.runs:
                if run.italic and run.text.strip():
                    output_file.write(f"       ITALIC_RUN: '{run.text}'\n")

with open(r'd:\laragon\www\erp-prototype\bab_terpisah\analysis_output.txt', 'w', encoding='utf-8') as f:
    for bab_file, label in [
        (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 1 - Pendahuluan.docx', 'BAB 1'),
        (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 2 - Landasan Teori.docx', 'BAB 2'),
        (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 3 - Metodologi Penelitian.docx', 'BAB 3'),
        (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 4 - Hasil dan Pembahasan.docx', 'BAB 4'),
        (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 5 - Kesimpulan dan Saran.docx', 'BAB 5'),
    ]:
        analyze_doc(bab_file, label, f)

print("Analysis saved to analysis_output.txt")
