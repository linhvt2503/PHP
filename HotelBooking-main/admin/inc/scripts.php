<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    function alert(type, msg,position='body'){
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger'
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        if(position=='body'){
            document.body.append(element);
        }
        else {
            document.getElementById(position).appendChild(element);
            element.classList.add('custom-alert');
        }


        setTimeout(remAlert, 2000);

        // Thêm style cho alert
        element.firstElementChild.style.position = 'fixed';
        element.firstElementChild.style.top = '80px';
        element.firstElementChild.style.right = '25px';
        element.firstElementChild.style.zIndex = '1111';

        // Tự động xóa alert sau 2 giây
        setTimeout(() => {
            element.firstElementChild.classList.remove('show');
            setTimeout(() => {
                element.remove();
            }, 300);
        }, 2000);
    }

    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }

    function setActive() {
        let navbar=document.getElementById('dashboard-menu');
        let a_tags= navbar.getElementsByTagName('a');

        for(i=0; i<a_tags.length;i++){
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if(document.location.href.indexOf(file_name) >= 0){
                a_tags[i].classList.add('active');
            }
        }
    }
    setActive();
</script>