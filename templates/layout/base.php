<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Wedding Decoration Rental' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>
    
    <!-- Main Content -->
    <main>
        <?php include __DIR__ . '/../pages/' . ($page ?? 'home') . '.php'; ?>
    </main>
    
    <!-- Footer -->
    <?php if (!isset($current_page) || $current_page !== 'admin'): ?>
        <?php include __DIR__ . '/../components/footer.php'; ?>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
</body>
</html>