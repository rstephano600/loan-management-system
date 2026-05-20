function runAjaxConfirm({title, text, icon, confirmText, confirmColor, url, method = 'POST', onSuccess = null}) {

    Swal.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor || '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText || 'Yes',
    }).then((result) => {

        if (result.isConfirmed) {

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(res => {

                if (res.success) {

                    Swal.fire({
                        icon: 'success',
                        title: res.message || 'Done',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    if (onSuccess) onSuccess(res);

                } else {
                    Swal.fire('Error', res.message || 'Failed', 'error');
                }

            })
            .catch(() => {
                Swal.fire('Error', 'Server error', 'error');
            });
        }
    });
}

// 🔴 DELETE
function confirmDelete(id) {
    runAjaxConfirm({
        title: 'Delete Record?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        confirmText: 'Yes, delete',
        confirmColor: '#d33',
        url: `/items/${id}`, // 🔁 adjust base URL
        method: 'DELETE',
        onSuccess: () => {
            document.getElementById('row-' + id)?.remove();
        }
    });
}

// 🟢 APPROVE
function confirmApprove(id) {
    runAjaxConfirm({
        title: 'Approve this?',
        text: 'Proceed with approval',
        icon: 'question',
        confirmText: 'Yes, approve',
        confirmColor: '#28a745',
        url: `/items/${id}/approve`
    });
}

// 🔵 ACCEPT
function confirmAccept(id) {
    runAjaxConfirm({
        title: 'Accept request?',
        text: 'Are you sure?',
        icon: 'info',
        confirmText: 'Yes, accept',
        confirmColor: '#17a2b8',
        url: `/items/${id}/accept`
    });
}




