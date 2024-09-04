<!-- resources/views/marketplace.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Minecraft Marketplace'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/appblade.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body>

    <header>
        <!-- Larger image on the top left -->
        <img src="<?php echo e(asset('Mine1.png')); ?>" alt="Logo"> <!-- Update with correct path if needed -->
        <h1><?php echo $__env->yieldContent('header'); ?></h1>
    </header>

    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php elseif(session('error')): ?>
            <div class="alert alert-error">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\e_commerce\resources\views/layouts/app.blade.php ENDPATH**/ ?>