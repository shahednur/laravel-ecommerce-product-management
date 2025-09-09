# Laravel E-commerce Product Management

An internship project built with **Laravel 12**, **Vue.js/Laravel Blade**, and **Tailwind CSS**.  
This project demonstrates product & category management with **CRUD operations**, **Many-to-Many relationships**, and a **responsive UI**.

---

## 🚀 Features
- Fresh Laravel 12 project setup
- Vue.js integration or Laravel Blade
- Tailwind CSS setup for styling
- Product & Category CRUD (Create, Read, Update, Delete)
- Many-to-Many relationship (Product ↔ Categories)
- Responsive UI (Tailwind CSS)
- GitHub/GitLab ready with `.env.example`

---

## 🛠️ Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/laravel-ecommerce-product-management.git
   cd laravel-ecommerce-product-management
   ```
---

2. Install dependencies:
```bash
    composer install
    npm install && npm run dev
```

---

3. Configure environment:
```bash
    cp .env.example .env
    php artisan key:generate
```

---

4. Run migrations:
```bash
    php artisan migrate
```

---

5. Start development server:
```bash
    php artisan serve
```

---

## 📂 Project Structure
```bash
laravel-ecommerce-product-management/
├── app/
│   ├── Models/        # Eloquent models (Product, Category)
│   ├── Http/          # Controllers, Requests
├── resources/
│   ├── views/         # Blade templates
│   ├── js/            # Vue components (if Vue enabled)
│   ├── css/           # Tailwind styles
├── routes/
│   ├── web.php        # Routes
```


