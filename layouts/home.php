<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Техническое задание</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
   
    <div class="container">
        <div class="row">
            <div class="col-md-12">
              <form action="/API/Contacts/addContact" method="post">
                <div class="row">
                   <div class="col-md-12 mb-3">
                       <h2 class="pb-2">Оформить контакт</h2>
                       <input type="number" class="form-control" name="source_id" id="source_id" placeholder="Source ID">
                       <div class="form-message"></div>
                   </div>
                   <div class="col-md-12 mb-3">
                       <div class="row contact-items">
                           <div class="col-md-3 mb-3">
                               <label for="name">Имя</label>
                               <input type="text" class="form-control" name="items[0][name]" id="name" placeholder="Иван Иванов">
                               <div class="form-message"></div>
                           </div>
                           <div class="col-md-3 mb-3">
                               <label for="email">Эл. почта</label>
                               <input type="email" class="form-control" name="items[0][email]" id="email" placeholder="example@example.com">
                               <div class="form-message"></div>
                           </div>
                           <div class="col-md-4 mb-3">
                               <label for="phone">Номер телефона</label>
                               <input type="tel" class="form-control masked-phone" name="items[0][phone]" id="phone" data-phonemask="+7 (___) ___-__-__" placeholder="Телефон">
                               <div class="form-message"></div>
                           </div>
                           <div class="col-md-1 mb-3">
                                <label></label>
                                <button class="btn btn-danger btn-block mt-2 remove-item" type="button">-</button>
                           </div>
                           <div class="col-md-1 mb-3">
                                <label></label>
                                <button class="btn btn-success btn-block mt-2 add-item" type="button">+</button>
                           </div>
                       </div>
                   </div>
                </div>

                <button class="btn btn-primary btn-lg btn-block" name="add" type="submit">
                    Добавить контакт
                </button>
                <div class="form-message text-center"></div>
              </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <h2 class="pt-4 pb-2">Kонтактная книжка</h2>
                <input type="text" class="form-control" name="search" placeholder="Search phone">
            </div>
            <div class="col-md-12">
                <table class="table"></table>
            </div>
        </div>
        
    </div>
    
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery.form.js"></script>
    <script src="/assets/js/phone-mask.min.js"></script>
    <script src="/assets/js/main.js"></script>
    
</body>
</html>