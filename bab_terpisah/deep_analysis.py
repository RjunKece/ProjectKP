"""Deep analysis of docx files - citations, headings, and foreign words."""
import re
from docx import Document

def analyze_deep(filepath, label, output_file):
    output_file.write(f"\n{'='*80}\n")
    output_file.write(f"DEEP ANALYSIS: {label}\n")
    output_file.write(f"{'='*80}\n")
    
    doc = Document(filepath)
    
    prev_heading_level = None
    prev_was_heading = False
    
    for i, para in enumerate(doc.paragraphs):
        text = para.text.strip()
        style_name = para.style.name if para.style else "None"
        
        # Detect heading levels
        is_heading = False
        heading_level = 0
        if 'heading' in style_name.lower() or 'head' in style_name.lower():
            is_heading = True
            # Try to extract level from style name
            nums = re.findall(r'\d+', style_name)
            if nums:
                heading_level = int(nums[0])
            
        if text:
            # Show heading transitions (potential missing intro text)
            if is_heading:
                if prev_was_heading and prev_heading_level is not None:
                    output_file.write(f"  *** HEADING-TO-HEADING TRANSITION: Level {prev_heading_level} -> Level {heading_level} ***\n")
                output_file.write(f"[{i:04d}] [HEADING L{heading_level}] [{style_name}] {text[:200]}\n")
                prev_heading_level = heading_level
                prev_was_heading = True
            else:
                prev_was_heading = False
                
            # Find ALL citation patterns
            # Pattern 1: (Author, Year) or (Author et al, Year) 
            citations = re.findall(r'\([^)]*\d{4}[^)]*\)', text)
            if citations:
                for c in citations:
                    output_file.write(f"  CITATION: {c}\n")
            
            # Pattern 2: Author (Year) - narrative citation
            narrative = re.findall(r'[A-Z][a-z]+(?:\s+(?:et\s+al\.?|dan|and)\s+[A-Z][a-z]+)?\s*\(\d{4}\)', text)
            if narrative:
                for n in narrative:
                    output_file.write(f"  NARRATIVE_CITATION: {n}\n")
            
            # Find English/foreign words that should be italic
            # Common English/tech words that appear in Indonesian academic text
            english_words = re.findall(r'\b(?:enterprise|resource|planning|cloud|computing|on-premise|framework|software|hardware|database|network|open-source|utility-first|tree-shaking|dark\s*mode|light\s*mode|design\s*token|breakpoint|prototype|standalone|spreadsheet|real-time|usable|reusable|timeline|proof\s*of\s*concept|waterfall|scaffolding|login|logout|middleware|routing|version\s*control|migration|seeder|command-line|interface|toggle|dropdown|modal|line\s*chart|bar\s*chart|pie\s*chart|doughnut\s*chart|radar\s*chart|scatter\s*plot|canvas|override|tools|dashboard|overview|engagement|marketplace|content\s*creator|advertiser|digital\s*marketing|affiliate|online|black-box\s*testing|equivalence\s*partitioning|boundary\s*value|decision\s*table|stress\s*testing|load\s*testing|localhost|deployment|production-ready|dummy|backup|input|output|debugging|split-screen|branding|rendering|responsive|collapse|scroll|session|hashing|cache|browser|wireframe|glassmorphism|gradient|micro-animations|hover)\b', text, re.IGNORECASE)
            if english_words:
                # Check which ones are NOT already italic
                italic_texts = [run.text for run in para.runs if run.italic and run.text.strip()]
                for word in set(english_words):
                    is_italic = any(word.lower() in it.lower() for it in italic_texts)
                    if not is_italic:
                        output_file.write(f"  FOREIGN_NOT_ITALIC: '{word}'\n")

files = [
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 1 - Pendahuluan.docx', 'BAB 1'),
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 2 - Landasan Teori.docx', 'BAB 2'),
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 3 - Metodologi Penelitian.docx', 'BAB 3'),
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 4 - Hasil dan Pembahasan.docx', 'BAB 4'),
    (r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 5 - Kesimpulan dan Saran.docx', 'BAB 5'),
]

with open(r'd:\laragon\www\erp-prototype\bab_terpisah\deep_analysis_output.txt', 'w', encoding='utf-8') as f:
    for filepath, label in files:
        analyze_deep(filepath, label, f)

print("Deep analysis saved to deep_analysis_output.txt")
