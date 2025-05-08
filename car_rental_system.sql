

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for table `cars`
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    brand VARCHAR(100),
    image VARCHAR(255),
    rent_per_day DECIMAL(10, 2) NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available'
);

-- Table structure for table `bookings`
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Sample data for cars table
INSERT INTO cars (model, brand, image, rent_per_day, status) VALUES
('Toyota Camry', 'Toyota', 'car1.jpg', 50.00, 'available'),
('Honda Civic', 'Honda', 'car2.jpg', 45.00, 'available'),
('Hyundai', 'Hyundai', 'Screenshot_2025_01_29_211744.png', 30000.00, 'available'),
('Nano', 'Tata', 'Screenshot_2025_04_27_235729.png', 10000.00, 'available'),
('Sumo', 'Tata', 'Screenshot_2025_02_10_203814.png', 5000.00, 'available');
