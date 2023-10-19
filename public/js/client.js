function launchLogout(){
    Swal.fire({
        title: 'You are about to logout',
        text: 'Do you really want to logout?',
        showCancelButton: true,
        confirmButtonText: 'Logout',
        dangerMode: true
      }).then((willLogout) => {
        if (willLogout.isConfirmed) {
          Swal.fire("You have been logged out successfully.", "", "success").then((done) => {
            window.location.replace("/logout");
          });
        } else {
          Swal.fire("You did not logout.", "", "info");
        }
      });
}
