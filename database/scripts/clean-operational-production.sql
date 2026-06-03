-- =============================================================================
-- Supabase PRODUCTION — FINAL CLEANUP & MINIMAL DEMO DATA
-- Jalankan: Supabase Dashboard → SQL Editor → Run
-- =============================================================================
-- STEP 1: Hapus SEMUA data operasional (AMAN: users, roles, divisions, daily_targets TETAP)
-- STEP 2: Hapus karyawan dummy berlebih (sisakan 1 per divisi + super admin)
-- STEP 3: Seed data demo minimal
-- =============================================================================

-- ===== STEP 1: BERSIHKAN DATA OPERASIONAL =====
TRUNCATE TABLE report_responses, reports, activities RESTART IDENTITY CASCADE;

-- ===== STEP 2: HAPUS USER KARYAWAN BERLEBIH (opsional — jalankan jika terlalu banyak) =====
-- Ini menyisakan 1 karyawan per divisi + semua super_admin.
-- UNCOMMENT baris di bawah HANYA jika ingin mengurangi user:
--
-- DELETE FROM users
-- WHERE id NOT IN (
--     -- Keep super_admin
--     SELECT u.id FROM users u
--     JOIN roles r ON u.role_id = r.id
--     WHERE r.nama_role = 'super_admin'
--     UNION
--     -- Keep 1 karyawan per divisi (yang id-nya paling kecil)
--     SELECT MIN(u.id) FROM users u
--     JOIN roles r ON u.role_id = r.id
--     WHERE r.nama_role = 'karyawan' AND u.division_id IS NOT NULL
--     GROUP BY u.division_id
-- );

-- ===== STEP 3: SEED DEMO ACTIVITIES (ringan, cukup untuk demo) =====
-- Insert beberapa aktivitas demo untuk karyawan yang ada
INSERT INTO activities (user_id, tanggal, deskripsi, status, created_at, updated_at)
SELECT
    u.id,
    CURRENT_DATE - (s.day_offset * INTERVAL '1 day'),
    CASE s.day_offset
        WHEN 0 THEN 'Menyelesaikan tugas harian divisi'
        WHEN 1 THEN 'Meeting koordinasi tim'
        WHEN 2 THEN 'Menyiapkan laporan mingguan'
    END,
    CASE s.day_offset
        WHEN 0 THEN 'completed'
        WHEN 1 THEN 'completed'
        WHEN 2 THEN 'in_progress'
    END,
    NOW(),
    NOW()
FROM users u
JOIN roles r ON u.role_id = r.id
CROSS JOIN (SELECT 0 AS day_offset UNION SELECT 1 UNION SELECT 2) s
WHERE r.nama_role = 'karyawan'
ORDER BY u.id, s.day_offset;

-- ===== STEP 4: SEED DEMO REPORTS (2 laporan saja) =====
INSERT INTO reports (title, type, scope, description, status, priority, start_date, created_by, created_at, updated_at)
VALUES
    ('Laporan Kinerja Bulanan', 'performance', 'company',
     'Ringkasan kinerja seluruh divisi bulan ini.', 'ready', 'normal',
     DATE_TRUNC('month', CURRENT_DATE),
     (SELECT id FROM users u JOIN roles r ON u.role_id = r.id WHERE r.nama_role = 'super_admin' LIMIT 1),
     NOW(), NOW()),
    ('Evaluasi Divisi Marketing', 'department', 'division',
     'Evaluasi performa divisi marketing Q2.', 'ready', 'normal',
     CURRENT_DATE - INTERVAL '7 days',
     (SELECT id FROM users u JOIN roles r ON u.role_id = r.id WHERE r.nama_role = 'super_admin' LIMIT 1),
     NOW(), NOW());

-- Update division_id untuk laporan divisi
UPDATE reports SET division_id = (SELECT id FROM divisions WHERE nama_divisi = 'Marketing' LIMIT 1)
WHERE title = 'Evaluasi Divisi Marketing' AND scope = 'division';

-- ===== VERIFIKASI =====
SELECT 'activities' as tabel, COUNT(*) as total FROM activities
UNION ALL SELECT 'reports', COUNT(*) FROM reports
UNION ALL SELECT 'report_responses', COUNT(*) FROM report_responses
UNION ALL SELECT 'users', COUNT(*) FROM users
UNION ALL SELECT 'roles', COUNT(*) FROM roles
UNION ALL SELECT 'divisions', COUNT(*) FROM divisions
UNION ALL SELECT 'daily_targets', COUNT(*) FROM daily_targets;
