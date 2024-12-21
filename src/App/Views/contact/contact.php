<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Liên Hệ - PowerGym</title>
   <!-- CSS Links -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <style>
       body {
           background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
       }
       
       .contact-section {
           padding: 100px 0;
       }
       
       .contact-header {
           text-align: center;
           margin-bottom: 60px;
           color: #2c3e50;
       }
       
       .contact-header h1 {
           font-weight: 700;
           margin-bottom: 25px;
           position: relative;
           display: inline-block;
           font-size: 2.5rem;
       }
       
       .contact-header h1:after {
           content: '';
           position: absolute;
           width: 80px;
           height: 3px;
           background: #007bff;
           bottom: -10px;
           left: calc(50% - 40px);
       }
       
       .contact-form {
           background: white;
           padding: 50px;
           border-radius: 15px;
           box-shadow: 0 0 30px rgba(0,0,0,0.1);
           max-width: 1000px;
           margin: auto;
       }
       
       .contact-info {
           margin-bottom: 50px;
       }
       
       .contact-info-item {
           display: flex;
           align-items: center;
           margin-bottom: 20px;
           padding: 25px;
           background: #f8f9fa;
           border-radius: 10px;
           transition: all 0.3s ease;
           height: 100%;
       }
       
       .contact-info-item:hover {
           transform: translateY(-5px);
           box-shadow: 0 5px 15px rgba(0,0,0,0.1);
       }
       
       .contact-icon {
           width: 60px;
           height: 60px;
           background: #007bff;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           margin-right: 15px;
           color: white;
           font-size: 1.2rem;
       }
       
       .form-control {
           border: 2px solid #e9ecef;
           padding: 15px;
           margin-bottom: 25px;
           transition: all 0.3s ease;
           border-radius: 8px;
       }
       
       .form-control:focus {
           border-color: #007bff;
           box-shadow: none;
       }
       
       .btn-submit {
           background: #007bff;
           color: white;
           padding: 15px 40px;
           border: none;
           border-radius: 30px;
           font-weight: 600;
           transition: all 0.3s ease;
           font-size: 1.1rem;
       }
       
       .btn-submit:hover {
           background: #0056b3;
           transform: translateY(-2px);
       }
       
       .success-message {
           display: none;
           text-align: center;
           color: #28a745;
           margin-top: 20px;
           padding: 30px;
           border-radius: 10px;
           background: #f8fff8;
           border: 2px solid #28a745;
       }
       
       .form-group {
           position: relative;
           margin-bottom: 25px;
       }
       
       .form-group label {
           font-weight: 500;
           margin-bottom: 10px;
           display: block;
       }
   </style>
</head>
<body>
   <div class="container contact-section">
       <div class="contact-header">
           <h1>Liên Hệ Với Chúng Tôi</h1>
           <p>Hãy để lại thông tin, chúng tôi sẽ liên hệ với bạn sớm nhất</p>
       </div>
       
       <div class="contact-form">
           <div class="contact-info">
               <div class="row">
                   <div class="col-md-4">
                       <div class="contact-info-item">
                           <div class="contact-icon">
                               <i class="fas fa-map-marker-alt"></i>
                           </div>
                           <div>
                               <h5>Địa Chỉ</h5>
                               <p>19 Lê Duẩn, Quyết tâm, TP-Sơn La</p>
                           </div>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="contact-info-item">
                           <div class="contact-icon">
                               <i class="fas fa-phone"></i>
                           </div>
                           <div>
                               <h5>Điện Thoại</h5>
                               <p>+84 899 813 764</p>
                           </div>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="contact-info-item">
                           <div class="contact-icon">
                               <i class="fas fa-envelope"></i>
                           </div>
                           <div>
                               <h5>Email</h5>
                               <p>info@powergym.com</p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
            <form id="contactForm">
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Họ Tên</label>
                           <input type="text" class="form-control" id="name" required>
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="email">Email</label>
                           <input type="email" class="form-control" id="email" required>
                       </div>
                   </div>
               </div>
               <div class="form-group">
                   <label for="subject">Tiêu Đề</label>
                   <input type="text" class="form-control" id="subject" required>
               </div>
               <div class="form-group">
                   <label for="message">Tin Nhắn</label>
                   <textarea class="form-control" id="message" rows="5" required></textarea>
               </div>
               <div class="text-center mt-4">
                   <button type="submit" class="btn btn-submit">
                       <i class="fas fa-paper-plane me-2"></i>Gửi Tin Nhắn
                   </button>
               </div>
           </form>
           <div class="success-message" id="successMessage">
               <i class="fas fa-check-circle fa-3x mb-3"></i>
               <h4>Cảm ơn bạn đã liên hệ!</h4>
               <p>Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
           </div>
       </div>
   </div>
    <!-- Scripts -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
       $(document).ready(function() {
           // Form submission
           $('#contactForm').on('submit', function(e) {
               e.preventDefault();
               
               // Thêm hiệu ứng loading
               $('.btn-submit').prop('disabled', true).html(
                   '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...'
               );
               
               // Giả lập gửi form (thay thế bằng AJAX thực tế)
               setTimeout(function() {
                   $('#contactForm').slideUp();
                   $('#successMessage').fadeIn();
               }, 1500);
           });
            // Hiệu ứng cho input
           $('.form-control').focus(function() {
               $(this).parent().addClass('focused');
           }).blur(function() {
               if ($(this).val() === '') {
                   $(this).parent().removeClass('focused');
               }
           });
       });
   </script>
</body>
</html>