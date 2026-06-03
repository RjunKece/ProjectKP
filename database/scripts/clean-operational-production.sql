-- =============================================================================
-- Supabase PRODUCTION — Hapus SEMUA dummy (TRUNCATE = cepat untuk jutaan baris)
-- Jalankan: Supabase Dashboard → SQL Editor → Run
-- AMAN: users, roles, divisions, daily_targets TIDAK dihapus
-- =============================================================================

TRUNCATE TABLE report_responses, reports, activities RESTART IDENTITY CASCADE;

-- Verifikasi (harus 0):
-- SELECT COUNT(*) FROM activities;
-- SELECT COUNT(*) FROM reports;
-- SELECT COUNT(*) FROM report_responses;
-- SELECT COUNT(*) FROM users;
