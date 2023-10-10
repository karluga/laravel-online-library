<!-- Modal -->
<div class="modal fade" id="borrowModal" tabindex="-1" role="dialog" aria-labelledby="borrowModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowModalLabel">Borrow Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="borrowForm" action="/home/borrowBook" method="POST">
                    @csrf
                    <input type="hidden" id="bookId" name="book_id">
                    <div class="form-group">
                        <label for="borrowInterval">Borrow Interval</label>
                        <p class="text-muted">This time is only how long you estimate to read this book to inform other users about when this book could be available.</p>
                        <div class="input-group">
                            <input type="number" value="30" class="form-control" id="borrowInterval" name="borrow_interval" min="1" max="30" required>
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Weeks</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" data-weeks="1">1 week</a>
                                <a class="dropdown-item" href="#" data-weeks="2">2 weeks</a>
                                <a class="dropdown-item" href="#" data-weeks="3">3 weeks</a>
                                <a class="dropdown-item" href="#" data-weeks="4">4 weeks</a>
                            </div>
                        </div>                        
                        <small class="text-warning">Maximum interval to borrow a book is one month. If you wish to extend your reading time, please call the library.</small>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="wholeMonthCheckbox" checked>
                        <label class="form-check-label" for="wholeMonthCheckbox">Whole month</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to my Borrow List</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listener for "Borrow" buttons
    document.querySelectorAll('.borrow-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const bookId = button.getAttribute('data-book-id');
            const bookIdInput = document.getElementById('bookId');

            // Set the book ID in the modal form
            bookIdInput.value = bookId;

            // Trigger the modal manually
            var myModal = new bootstrap.Modal(document.getElementById("borrowModal"));
            myModal.show();
        });
    });
    // JavaScript to update the input field when a week is selected
    document.querySelectorAll('.dropdown-item').forEach(function(item) {
        item.addEventListener('click', function(event) {
            event.preventDefault();
            const weeks = parseInt(item.getAttribute('data-weeks'));
            const daysInput = document.getElementById('borrowInterval');
            daysInput.value = weeks * 7;
            document.getElementById('wholeMonthCheckbox').checked = false; // Uncheck the checkbox when the input is updated
        });
    });

    // JavaScript to check/uncheck the "Whole month" checkbox and set the input to 30 days
    const checkbox = document.getElementById('wholeMonthCheckbox');
    const daysInput = document.getElementById('borrowInterval');

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            daysInput.value = 30;
        } else {
            daysInput.value = ''; // Clear the input when the checkbox is unchecked
        }
    });
</script>