-- ==============================
-- DATABASE
-- ==============================
CREATE DATABASE IF NOT EXISTS ecommerce_clothing;
USE ecommerce_clothing;

-- ==============================
-- ROLES
-- ==============================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- ==============================
-- USER STATUSES
-- ==============================
CREATE TABLE user_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE
);

-- ==============================
-- USERS
-- ==============================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT,
    status_id INT,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (status_id) REFERENCES user_statuses(id)
);

-- ==============================
-- PRODUCT STATUSES
-- ==============================
CREATE TABLE product_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE
);

-- ==============================
-- BRANDS
-- ==============================
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE,
    logo VARCHAR(255),
    status_id INT,
    
    FOREIGN KEY (status_id) REFERENCES product_statuses(id)
);

-- ==============================
-- CATEGORIES
-- ==============================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    slug VARCHAR(150) UNIQUE,
    status_id INT,
    
    FOREIGN KEY (status_id) REFERENCES product_statuses(id)
);

-- ==============================
-- PRODUCTS
-- ==============================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    brand_id INT,
    status_id INT,
    name VARCHAR(200),
    slug VARCHAR(220) UNIQUE,
    description TEXT,
    base_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FOREIGN KEY (status_id) REFERENCES product_statuses(id)
);

-- ==============================
-- SIZES
-- ==============================
CREATE TABLE sizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) UNIQUE
);

-- ==============================
-- COLORS
-- ==============================
CREATE TABLE colors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30),
    hex_code VARCHAR(10)
);

-- ==============================
-- VARIANT STATUSES
-- ==============================
CREATE TABLE variant_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE
);

-- ==============================
-- PRODUCT VARIANTS
-- ==============================
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    size_id INT,
    color_id INT,
    status_id INT,
    sale_price DECIMAL(10,2),
    sku VARCHAR(100) UNIQUE,
    stock INT DEFAULT 0,

    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (size_id) REFERENCES sizes(id),
    FOREIGN KEY (color_id) REFERENCES colors(id),
    FOREIGN KEY (status_id) REFERENCES variant_statuses(id)
);

-- ==============================
-- PRODUCT IMAGES
-- ==============================
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    variant_id INT,
    image VARCHAR(255),
    is_main TINYINT(1) DEFAULT 0,

    FOREIGN KEY (variant_id) REFERENCES product_variants(id)
);

-- ==============================
-- CARTS
-- ==============================
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ==============================
-- CART ITEMS
-- ==============================
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT,
    variant_id INT,
    quantity INT DEFAULT 1,

    FOREIGN KEY (cart_id) REFERENCES carts(id),
    FOREIGN KEY (variant_id) REFERENCES product_variants(id)
);

-- ==============================
-- ORDER STATUSES
-- ==============================
CREATE TABLE order_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE
);

-- ==============================
-- ORDERS
-- ==============================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_status_id INT,
    order_number VARCHAR(50),
    subtotal DECIMAL(10,2),
    discount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2),
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (order_status_id) REFERENCES order_statuses(id)
);

-- ==============================
-- ORDER ITEMS
-- ==============================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    variant_id INT,
    price DECIMAL(10,2),
    quantity INT,

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (variant_id) REFERENCES product_variants(id)
);

-- ==============================
-- SHIPPING ADDRESSES
-- ==============================
CREATE TABLE shipping_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),

    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ==============================
-- PAYMENT STATUSES
-- ==============================
CREATE TABLE payment_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) UNIQUE
);

-- ==============================
-- PAYMENTS
-- ==============================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    status_id INT,
    transaction_id VARCHAR(150),
    amount DECIMAL(10,2),
    method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (status_id) REFERENCES payment_statuses(id)
);

-- ==============================
-- COUPONS
-- ==============================
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE,
    discount_type ENUM('percentage','fixed'),
    discount_value DECIMAL(10,2),
    min_amount DECIMAL(10,2),
    expiry_date DATE,
    status_id INT
);

-- ==============================
-- REVIEWS
-- ==============================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    rating TINYINT,
    comment TEXT,
    status_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);