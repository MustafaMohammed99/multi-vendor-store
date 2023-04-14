// Add a click event listener to all "Cancel" buttons
const cancelButtons = document.querySelectorAll('.cancel-btn');
cancelButtons.forEach(button => {
    button.addEventListener('click', () => {
        const model = button.getAttribute('data-model');
        const type = button.getAttribute('data-type');
        const id = button.getAttribute('data-id');
        const image_path = button.getAttribute('data-image');

        // Send an AJAX request to delete the image
        fetch('/delete-image', {
                method: 'delete',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf_token
                },
                body: JSON.stringify({
                    model: model,
                    type: type,
                    id: id,
                    image_path: image_path
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove the image from the DOM if it was deleted successfully
                console.log(data);
                if (data.status === 'success') {
                    button.parentNode.remove();
                }
                if (type === 'single') {
                    document.getElementById('filepond_single_image').style.display = 'block';
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
});
