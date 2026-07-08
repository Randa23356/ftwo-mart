#!/bin/bash

# Blade Template Scanner
# Usage: ./scan_blade.sh

echo "======================================"
echo "   Blade Template Health Scanner"
echo "======================================"

# 1. Clear Cache
echo "[1/3] Clearing Laravel Caches..."
php artisan view:clear
php artisan cache:clear
echo "Done."
echo ""

# 2. Run PHP Deep Scan
echo "[2/3] Running Deep Scan (Directives, HTML Tags, Braces)..."
php debug_blade.php
echo ""

# 3. Quick Grep for specific patterns (Double Check)
echo "[3/3] Performing Quick Pattern Check..."
echo "Checking for unclosed @if, @foreach, @section..."

# Find all blade files excluding layouts
find resources/views -name "*.blade.php" -not -path "*/layouts/*" | while read file; do
    # Count opens
    if_count=$(grep -o "@if" "$file" | wc -l)
    endif_count=$(grep -o "@endif" "$file" | wc -l)
    
    if [ "$if_count" -ne "$endif_count" ]; then
        echo "WARNING: Potential mismatched @if ($if_count) / @endif ($endif_count) in $file"
    fi

    # Basic check for odd number of single quotes (ignoring comments)
    # This is a bit rough but catches obvious issues
    quote_count=$(grep -v "{{\|}}" "$file" | grep -o "'" | wc -l)
    if [ $((quote_count % 2)) -ne 0 ]; then
        # echo "INFO: Odd number of single quotes in $file (Check manually)"
        :
    fi
done

echo ""
echo "======================================"
echo "   Scan Complete"
echo "======================================"
