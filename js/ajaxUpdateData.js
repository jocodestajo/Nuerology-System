<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // When the image is clicked
    $('.approve-button').on('click', function() {
        // Get the record ID from the data-id attribute
        var recordId = $(this).data('id');
        
        // Send AJAX request to update the database
        $.ajax({
            url: 'update_status.php', // The PHP script to handle the update
            method: 'POST',            // Use POST to send data
            data: { id: recordId },    // Send the record ID to the server
            success: function(response) {
                if (response.success) {
                    alert('Record updated successfully!');
                    // Optionally, you can update the image or status here
                } else {
                    alert('Error updating record.');
                }
            },
            error: function() {
                alert('An error occurred.');
            }
        });
    });
</script>
