**create_العقار.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New العقار</h2>

        <form id="create-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold text-sm mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter Name">
            </div>

            <div>
                <label for="description" class="text-slate-900 font-bold text-sm mb-2">Description</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter Description"></textarea>
            </div>

            <div>
                <label for="price" class="text-slate-900 font-bold text-sm mb-2">Price</label>
                <input type="number" id="price" name="price" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter Price">
            </div>

            <div>
                <label for="location" class="text-slate-900 font-bold text-sm mb-2">Location</label>
                <input type="text" id="location" name="location" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter Location">
            </div>

            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/العقار.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_العقار.php';
                    } else {
                        alert('Error creating new العقار');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** This code assumes you have jQuery and a backend PHP script (`../backend/العقار.php`) to handle the form submission. The backend script should validate and process the form data, and return a 'success' response if the data is valid and the record is created successfully.