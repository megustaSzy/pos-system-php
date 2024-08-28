<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View
                <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Print</a>
                <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
                if(isset($_GET['track'])) 
                {
                    if($_GET['track'] == '') {
                        ?>
                        <div class="text-center py-5">
                            <h5>No Tracking Number Found</h5>
                            <div>
                            <a href="orders.php" class="btn btn-primary">Go back to order</a>
                            </div>
                        </div>
                        <?php
                        return false;
                    }

                    $trackingNo = validate($_GET['track']);

                    $query = "SELECT o.*, c.* FROM orders o, customers c 
                                WHERE c.id = o.customer_id AND tracking_no='$trackingNo' 
                                ORDER BY o.id DESC";

                    $orders = mysqli_query($conn, $query);
                    if($orders) {
                        if(mysqli_num_rows($orders) > 0) {
                            $orderData = mysqli_fetch_assoc($orders);
                            $orderId = $orderData['id'];

                            ?>
                            <div class="card card-body shadow border-1 mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Order Details</h4>
                                        <label class="mb-1">
                                            Tracking No : 
                                            <span class="fw-bold"><?= $orderData['tracking_no']; ?></span>
                                        </label>
                                        <br/>
                                        <label class="mb-1">
                                            Tanggal Order : 
                                            <span class="fw-bold"><?= $orderData['order_date']; ?></span>
                                        </label>
                                        <br/>
                                        <label class="mb-1">
                                            Status Order : 
                                            <span class="fw-bold"><?= $orderData['order_status']; ?></span>
                                        </label>
                                        <br/>
                                        <label class="mb-1">
                                            Payment Mode : 
                                            <span class="fw-bold"><?= $orderData['payment_mode']; ?></span>
                                        </label>
                                        <br/>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>User Details</h4>
                                        <label class="mb-1">
                                            Full Name : 
                                            <span class="fw-bold"><?= $orderData['name']; ?></span>
                                        </label>
                                        <br/>
                                        <label class="mb-1">
                                            Email Address : 
                                            <span class="fw-bold"><?= $orderData['email']; ?></span>
                                        </label>
                                        <br/>
                                        <label class="mb-1">
                                            Phone Number : 
                                            <span class="fw-bold"><?= $orderData['phone']; ?></span>
                                        </label>
                                        <br/>
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else {
                            echo '<h5>No Record Found</h5>';
                            return false;
                        }

                    } else {
                        echo '<h5>Something Went Wrong</h5>';
                    }
                }
                else
                {
                    ?>
                    <div class="text-center py-5">
                        <h5>No Tracking Number Found</h5>
                        <div>
                            <a href="orders.php" class="btn btn-primary">Go back to order</a>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>