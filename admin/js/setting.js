$(document).ready(function(){
    $("#back_button").click(function(){
        $("#background").toggle();
        $("#net_form").hide();
        $("#windows_form").hide();
        $('#new_user_form').hide();
        $('#update_user_form').hide();
    })
    $("#net_button").click(function(){
        $("#net_form").toggle();
        $("#background").hide();
        $("#windows_form").hide();
        $('#new_user_form').hide();
        $('#update_user_form').hide();
    })
    $("#windows_button").click(function(){
        $("#windows_form").toggle();
        $("#background").hide();
        $("#net_form").hide();
        $('#new_user_form').hide();
        $('#update_user_form').hide();
    })
    $('#new_user_button').click(function(){
        $('#new_user_form').toggle();
        $('#update_user_form').hide();
        $("#net_form").hide();
        $("#background").hide();
        $("#windows_form").hide();

    })
    $('#new_user').click(function(){
        $('#div_new_user').toggle();
        $('#div_update_user').hide();
    })
    $('#update_user').click(function(){
        $('#div_update_user').toggle();
        $('#div_new_user').hide();
    })
})
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fileUpload').addEventListener('change', function(event) {
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        const files = event.target.files;
        let isValid = true;
        for (const file of files) {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                isValid = false;
                break;
            }
        }
        const errorMessage = document.getElementById('error-message');
        if (!isValid) {
            errorMessage.textContent = 'صيغة الملف غير مدعومة. الرجاء اختيار ملفات بصيغة jpg, jpeg, png، أو pdf.';
            event.target.value = ''; // إعادة تعيين قيمة المدخل لمنع تحميل الملف غير المدعوم
        } else {
            errorMessage.textContent = '';
        }
    });

    document.getElementById('background_submit').addEventListener('click', function() {
        const fileInput = document.getElementById('fileUpload');
        const file = fileInput.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                localStorage.setItem('backgroundImage', e.target.result);
                showAlert();
            }
            reader.readAsDataURL(file);
        } else {
            alert('يرجى اختيار صورة');
        }
    });
});

function showAlert() {
    document.getElementById('alertBox').style.display = 'block';
}

function closeAlert() {
    document.getElementById('alertBox').style.display = 'none';
}