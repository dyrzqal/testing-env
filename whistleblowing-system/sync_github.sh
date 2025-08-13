#!/bin/bash
echo "🔄 Sinkronisasi dengan GitHub"
echo "============================"

# 1. Pull perubahan terbaru
echo "📥 Pulling latest changes..."
git pull origin main

if [ $? -ne 0 ]; then
    echo "⚠️  Ada conflict atau error saat pull. Resolve dulu, lalu jalankan lagi."
    exit 1
fi

# 2. Add semua perubahan
echo "📦 Adding changes..."
git add .

# 3. Check ada perubahan atau tidak
if git diff --staged --quiet; then
    echo "ℹ️  Tidak ada perubahan untuk di-commit."
else
    # 4. Commit dengan pesan
    echo "💬 Masukkan commit message (atau enter untuk default):"
    read -p "Message: " commit_msg
    
    if [ -z "$commit_msg" ]; then
        commit_msg="📦 Update: $(date '+%Y-%m-%d %H:%M')"
    fi
    
    echo "💾 Committing changes..."
    git commit -m "$commit_msg"
    
    # 5. Push ke GitHub
    echo "🚀 Pushing to GitHub..."
    git push origin main
    
    if [ $? -eq 0 ]; then
        echo "✅ SUCCESS! Perubahan berhasil di-push ke GitHub!"
    else
        echo "❌ Error: Push gagal."
    fi
fi
