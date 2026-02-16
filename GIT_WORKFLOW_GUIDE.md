# Panduan Git Workflow - Branch ke Main

Panduan lengkap untuk bekerja dengan Git branch dan merge ke main.

## 📋 Daftar Isi
1. [Membuat Branch Baru](#1-membuat-branch-baru)
2. [Bekerja di Branch](#2-bekerja-di-branch)
3. [Commit Perubahan](#3-commit-perubahan)
4. [Merge ke Main](#4-merge-ke-main)
5. [Push ke Remote](#5-push-ke-remote)
6. [Cleanup](#6-cleanup)
7. [Tips & Troubleshooting](#tips--troubleshooting)

---

## 1. Membuat Branch Baru

### Cek branch yang ada
```bash
git branch
```

### Buat branch baru dari main
```bash
# Pastikan di main dulu
git checkout main

# Pull perubahan terbaru
git pull origin main

# Buat dan pindah ke branch baru
git checkout -b nama-branch-baru
```

**Contoh:**
```bash
git checkout -b feature-login
git checkout -b fix-bug-payment
git checkout -b update-ui-dashboard
```

### Atau buat branch tanpa pindah
```bash
git branch nama-branch-baru
git checkout nama-branch-baru
```

---

## 2. Bekerja di Branch

### Cek branch aktif
```bash
git branch
# Branch dengan tanda * adalah branch aktif
```

### Pindah antar branch
```bash
git checkout nama-branch
```

### Cek status perubahan
```bash
git status
```

---

## 3. Commit Perubahan

### Tambahkan file yang diubah
```bash
# Tambah semua file
git add .

# Atau tambah file spesifik
git add nama-file.php
git add app/Http/Controllers/
```

### Commit dengan pesan
```bash
git commit -m "Pesan commit yang jelas"
```

**Contoh pesan commit yang baik:**
```bash
git commit -m "Add login feature with validation"
git commit -m "Fix payment calculation bug"
git commit -m "Update dashboard UI for admin role"
```

### Cek history commit
```bash
git log --oneline
```

---

## 4. Merge ke Main

### Langkah 1: Pastikan branch update sudah commit semua
```bash
git status
# Harus "nothing to commit, working tree clean"
```

### Langkah 2: Pindah ke branch main
```bash
git checkout main
```

### Langkah 3: Pull perubahan terbaru dari remote
```bash
git pull origin main
```

### Langkah 4: Merge branch ke main
```bash
git merge nama-branch-anda
```

**Contoh:**
```bash
git merge feature-login
```

### Jika ada conflict
```bash
# Git akan memberitahu file mana yang conflict
# Buka file tersebut dan selesaikan conflict
# Setelah selesai:
git add .
git commit -m "Resolve merge conflict"
```

---

## 5. Push ke Remote

### Push ke GitHub/GitLab
```bash
git push origin main
```

### Jika ditolak (rejected)
```bash
# Pull dulu, lalu push lagi
git pull origin main
git push origin main
```

### Push branch ke remote (opsional)
```bash
git push origin nama-branch
```

---

## 6. Cleanup

### Hapus branch lokal (setelah merge)
```bash
git branch -d nama-branch
```

### Hapus branch lokal (paksa)
```bash
git branch -D nama-branch
```

### Hapus branch di remote
```bash
git push origin --delete nama-branch
```

### Lihat semua branch (lokal dan remote)
```bash
git branch -a
```

---

## Tips & Troubleshooting

### ✅ Best Practices

1. **Selalu pull sebelum mulai kerja**
   ```bash
   git checkout main
   git pull origin main
   ```

2. **Buat branch dengan nama yang jelas**
   - ✅ `feature-user-authentication`
   - ✅ `fix-payment-bug`
   - ✅ `update-dashboard-ui`
   - ❌ `test`, `new`, `update`

3. **Commit sering dengan pesan yang jelas**
   ```bash
   git commit -m "Add user login validation"
   git commit -m "Fix null pointer in payment controller"
   ```

4. **Jangan commit langsung ke main**
   - Selalu buat branch untuk fitur/fix baru
   - Merge ke main setelah selesai

### 🔧 Troubleshooting

#### Problem: "Your branch is behind"
```bash
git pull origin main
```

#### Problem: "Please commit your changes or stash them"
```bash
# Simpan perubahan sementara
git stash

# Atau commit dulu
git add .
git commit -m "Work in progress"
```

#### Problem: Salah branch saat commit
```bash
# Pindahkan commit terakhir ke branch lain
git log --oneline  # Catat hash commit
git checkout branch-yang-benar
git cherry-pick <hash-commit>
```

#### Problem: Ingin undo commit terakhir
```bash
# Undo tapi tetap simpan perubahan
git reset --soft HEAD~1

# Undo dan buang perubahan
git reset --hard HEAD~1
```

#### Problem: Lupa nama branch
```bash
git branch -a
```

---

## 📝 Workflow Lengkap (Contoh)

```bash
# 1. Mulai dari main
git checkout main
git pull origin main

# 2. Buat branch baru
git checkout -b feature-review-system

# 3. Kerja dan commit
# ... edit file ...
git add .
git commit -m "Add review model and migration"

# ... edit lagi ...
git add .
git commit -m "Add review controller and routes"

# ... edit lagi ...
git add .
git commit -m "Add review UI in book detail page"

# 4. Merge ke main
git checkout main
git pull origin main
git merge feature-review-system

# 5. Push ke remote
git push origin main

# 6. Hapus branch (opsional)
git branch -d feature-review-system
```

---

## 🚀 Quick Reference

| Perintah | Fungsi |
|----------|--------|
| `git branch` | Lihat semua branch |
| `git checkout -b nama` | Buat dan pindah ke branch baru |
| `git checkout nama` | Pindah ke branch |
| `git add .` | Tambah semua perubahan |
| `git commit -m "pesan"` | Commit dengan pesan |
| `git status` | Cek status |
| `git log --oneline` | Lihat history |
| `git merge nama` | Merge branch ke branch aktif |
| `git push origin main` | Push ke remote |
| `git pull origin main` | Pull dari remote |
| `git branch -d nama` | Hapus branch |

---

## ⚠️ Hal yang Harus Dihindari

1. ❌ Jangan `git push -f` (force push) ke main
2. ❌ Jangan commit file sensitive (.env, password)
3. ❌ Jangan commit node_modules atau vendor
4. ❌ Jangan merge tanpa test dulu
5. ❌ Jangan lupa pull sebelum push

---

**Dibuat untuk:** E-SIPUS Project  
**Terakhir diupdate:** 7 Februari 2026
