#!/bin/bash
echo "🚀 Script untuk Push ke GitHub"
echo "================================"
echo ""
echo "Masukkan URL GitHub repository Anda:"
echo "Contoh: https://github.com/username/whistleblowing-system.git"
read -p "GitHub URL: " github_url

if [ -z "$github_url" ]; then
    echo "❌ URL tidak boleh kosong!"
    exit 1
fi

echo ""
echo "🔄 Menambahkan remote origin..."
git remote add origin "$github_url"

echo "🔄 Mengubah branch ke main..."
git branch -M main

echo "🚀 Pushing ke GitHub..."
git push -u origin main

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ SUCCESS! Repository berhasil di-push ke GitHub!"
    echo "🌐 Buka: $github_url"
else
    echo ""
    echo "❌ Error: Push gagal. Periksa URL dan credentials Anda."
fi
