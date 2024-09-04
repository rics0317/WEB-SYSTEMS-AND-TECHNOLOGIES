<!-- resources/views/products/index.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="assets/mp4/Mine.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<div class="container">
    <!-- Header Actions Container -->
    <div class="header-actions">
        <!-- Back Icon -->
        <a href="<?php echo e(route('home')); ?>" class="btn btn-secondary back-icon-container">
            <img src="back.png" alt="Back" class="back-icon"> 
        </a>
    </div>

    <!-- Centered Search Form -->
    <div class="heading-container">
        <!-- Search Form -->
        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="search-form">
            <input type="text" name="search" class="form-control search-input" placeholder="SEARCH" value="<?php echo e(request('search')); ?>">
            <button type="submit" class="btn search-btn">
                <img src="search-image.png" alt="Search" class="search-icon"> <!-- Replace with your Minecraft icon -->
            </button>
        </form>
    </div>

    <!-- Display Products -->
    <?php if($products->isEmpty()): ?>
        <div class="no-products-message">
            <p>No products found.</p>
        </div>
    <?php else: ?>
        <div class="products-container">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="Product Image" width="100">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </div>
                    <div class="product-details">
                        <h3><?php echo e($product->product_name); ?></h3>
                        <p><?php echo e($product->description); ?></p>
                        <p>Price: $<?php echo e(number_format($product->price, 2)); ?></p>
                        <p>Stock: <?php echo e($product->stock); ?></p>
                        <div class="product-actions">
                            <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn btn-warning">Edit</a>
                            <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php echo e($products->links()); ?>

</div>
<link rel="stylesheet" href="<?php echo e(asset('css/indexblade.css')); ?>">
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\e_commerce\resources\views/products/index.blade.php ENDPATH**/ ?>