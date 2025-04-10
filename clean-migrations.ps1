# Script pour nettoyer les fichiers de migration sans timestamp
Remove-Item -Path "database/migrations/create_books_table.php" -Force
Remove-Item -Path "database/migrations/create_categories_table.php" -Force
Remove-Item -Path "database/migrations/create_comments_table.php" -Force
Remove-Item -Path "database/migrations/create_roles_table.php" -Force
Remove-Item -Path "database/migrations/create_sales_table.php" -Force
Remove-Item -Path "database/migrations/create_user_role_table.php" -Force

Write-Host "Nettoyage des migrations terminé" -ForegroundColor Green
