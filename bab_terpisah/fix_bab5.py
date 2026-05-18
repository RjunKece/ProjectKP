"""Re-run just BAB 5 from backup."""
import shutil
shutil.copy2(
    r'd:\laragon\www\erp-prototype\bab_terpisah\backup\BAB 5 - Kesimpulan dan Saran.docx',
    r'd:\laragon\www\erp-prototype\bab_terpisah\BAB 5 - Kesimpulan dan Saran.docx'
)
print("Restored BAB 5 from backup")

from update_docs import process_bab
process_bab(5, 'BAB 5 - Kesimpulan dan Saran.docx')
print("Done!")
