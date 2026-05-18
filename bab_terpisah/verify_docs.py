"""Verify the updated documents."""
import re
from docx import Document

def verify_doc(filepath, label):
    print(f"\n{'='*70}")
    print(f"VERIFYING: {label}")
    print(f"{'='*70}")
    
    doc = Document(filepath)
    
    issues = []
    
    for i, para in enumerate(doc.paragraphs):
        text = para.text.strip()
        if not text:
            continue
        
        style_name = para.style.name if para.style else "None"
        
        # Check citations format
        citations = re.findall(r'\([^)]*\d{4}[^)]*\)', text)
        for c in citations:
            # Check for bad patterns
            if re.search(r'et al[^.]', c) and 'et al.' not in c:
                issues.append(f"  BAD CITATION (missing dot after et al): {c}")
            if re.search(r'\.\s*,\s*\d{4}', c):
                issues.append(f"  BAD CITATION (dot before comma+year): {c}")
            if re.search(r'\s,', c):
                issues.append(f"  BAD CITATION (space before comma): {c}")
            print(f"  CITATION [{i:04d}]: {c}")
        
        # Check italic foreign words
        italic_runs = [run.text for run in para.runs if run.italic and run.text.strip()]
        if italic_runs:
            print(f"  ITALIC [{i:04d}]: {italic_runs[:5]}{'...' if len(italic_runs) > 5 else ''}")
    
    if issues:
        print(f"\n  *** ISSUES FOUND ***")
        for issue in issues:
            print(issue)
    else:
        print(f"\n  No issues found!")

files = [
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 1 - Pendahuluan.docx', 'BAB 1'),
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 2 - Landasan Teori.docx', 'BAB 2'),
]

for filepath, label in files:
    verify_doc(filepath, label)
