# 🚀 Quick Start Guide

## Installation & Setup

### 1. Database Setup
Run this SQL to create the required tables:

```sql
-- Create Lessons Table
CREATE TABLE lessons (
    lessonId INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    ageRange VARCHAR(100) NOT NULL,
    duration INT NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255)
);

-- Create Questions Table
CREATE TABLE questions (
    questionId INT PRIMARY KEY AUTO_INCREMENT,
    lessonId INT NOT NULL,
    questionText TEXT NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255),
    goodAnswer VARCHAR(255) NOT NULL,
    FOREIGN KEY (lessonId) REFERENCES lessons(lessonId) ON DELETE CASCADE
);
```

### 2. Database Configuration
Edit `config.php` with your database credentials:

```php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'schooldb';      // Your database name
$DB_USER = 'root';          // Your username
$DB_PASS = '';              // Your password
```

### 3. File Permissions
Ensure folder permissions are correct:
```
lessons_project/
├── config.php (readable)
├── index.php (readable)
├── controllers/ (readable)
├── models/ (readable)
├── views/ (readable)
└── public/ (readable)
```

---

## 🎯 Access Points

### Admin Panel - Manage Lessons
```
http://localhost/lessons_project/index.php?action=lessonList
```
- View all lessons
- Add new lesson
- Edit existing lessons
- Delete lessons

### Admin Panel - Manage Questions
```
http://localhost/lessons_project/index.php?action=questionList
```
- View all questions
- Add new question
- Edit existing questions
- Delete questions

### Student Interface - Browse Lessons
```
http://localhost/lessons_project/index.php?action=lessonDisplay
```
(Or just `http://localhost/lessons_project/` - this is the default)

---

## 📋 Sample Data

### Add a Sample Lesson
1. Go to **Admin Panel → Lessons → Add New Lesson**
2. Fill in the form:
   - **Title**: "Introduction to Numbers"
   - **Age Range**: "3-5 years"
   - **Duration**: "15"
   - **Description**: "Learn to count from 1 to 10 with fun activities"
   - **Image**: (leave empty or paste image URL)
3. Click "Add Lesson"

### Add Sample Questions
1. Go to **Admin Panel → Questions → Add New Question**
2. Select lesson: "Introduction to Numbers"
3. Fill form:
   - **Question**: "How many apples are in a group of 3?"
   - **Option 1**: "2"
   - **Option 2**: "3"
   - **Option 3**: "4"
   - **Correct Answer**: "3"
4. Click "Add Question"

Repeat for more questions...

---

## 🧪 Testing the System

### Test Lessons
- ✅ View lesson list
- ✅ Click "View Lesson"
- ✅ See lesson details
- ✅ Click "Take Quiz"

### Test Quiz
- ✅ Answer all questions
- ✅ Click "Submit Quiz"
- ✅ Review results
- ✅ Click "Retake Quiz"

### Test Admin Panel
- ✅ Add new lesson
- ✅ Edit lesson
- ✅ Delete lesson
- ✅ Add question
- ✅ Edit question
- ✅ Delete question

---

## 🐛 Troubleshooting

### Database Connection Error
**Error**: "Database connection failed"
- Check `config.php` credentials
- Ensure MySQL is running
- Create database `schooldb`

### Form Won't Submit
**Error**: Nothing happens when submitting form
- Check browser console (F12 → Console)
- Check PHP error logs
- Ensure all required fields are filled

### Quiz Results Show Incorrectly
**Error**: Wrong answers marked as correct
- Check exact spelling of correct answer
- Ensure correct answer matches option text exactly
- No extra spaces or special characters

### Styles Not Loading
**Error**: Page looks plain without colors
- Check file paths in HTML
- Clear browser cache (Ctrl+Shift+Delete)
- Check if CSS files exist

### JavaScript Not Working
**Error**: Progress bar or animations don't work
- Check browser console for errors
- Ensure JavaScript files are accessible
- Check file paths are correct

---

## 🎨 Customization Examples

### Change Primary Color
Edit **public/css/back.css** and **public/css/front.css**:

Find:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

Replace with your colors:
```css
background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
```

### Change Button Text
Edit view files, e.g., `views/back/lessonAdd.php`:

Find:
```html
<button type="submit" class="btn btn-primary">Add Lesson</button>
```

Replace with:
```html
<button type="submit" class="btn btn-primary">Create Lesson</button>
```

### Change Page Title
Edit view files, find `<title>` tag:

```html
<title>Add Lesson - Admin</title>
```

### Hide Navigation
Edit CSS in `public/css/back.css`:

Find `.navbar { width: 250px; ... }`

Change to: `display: none;`

---

## 📊 Database Queries Reference

### Get All Lessons
```php
$stmt = $pdo->query("SELECT * FROM lessons ORDER BY lessonId DESC");
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### Get Lesson by ID
```php
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE lessonId = ?");
$stmt->execute([$id]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);
```

### Get Questions for Lesson
```php
$stmt = $pdo->prepare("SELECT * FROM questions WHERE lessonId = ?");
$stmt->execute([$lessonId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

---

## 🔐 Security Features

✅ **XSS Protection**
- All output escaped with `htmlspecialchars()`

✅ **SQL Injection Protection**
- All queries use prepared statements with placeholders

✅ **Form Validation**
- Client-side validation with JavaScript
- Server-side validation in PHP

✅ **Data Integrity**
- Correct answer validated against options
- Foreign key constraints in database

---

## 📱 Mobile Optimization

The site is fully responsive:

- **Desktop**: Fixed sidebar navigation
- **Tablet**: Adjusted layout, flexible grid
- **Mobile**: Single column, full-width elements

Test on mobile:
1. Open DevTools (F12)
2. Click device emulation icon
3. Test on iPhone/Android

---

## 📈 Performance Tips

### For Better Performance
1. **Optimize Images**: Use compressed images in lessons
2. **Enable Caching**: Add cache headers in PHP
3. **Database Indexing**: Index frequently searched columns
4. **CDN**: Use CDN for CSS/JS files

### Monitor Performance
- Use browser DevTools (F12 → Performance)
- Check page load time
- Monitor database queries

---

## 🆘 Getting Help

### Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Blank page | Check `config.php` credentials |
| Forms don't work | Check file upload permissions |
| Styles missing | Clear browser cache, check paths |
| SQL errors | Check table names, column names |
| Navigation broken | Check `index.php` router switch case |

### Check Error Logs
```bash
# PHP errors
tail -f /var/log/php-errors.log

# MySQL errors
tail -f /var/log/mysql/error.log
```

---

## 🎓 Learning Resources

This system demonstrates:
- ✅ MVC Pattern
- ✅ PDO Database Queries
- ✅ HTML Forms
- ✅ CSS Responsive Design
- ✅ JavaScript DOM Manipulation
- ✅ PHP Session Management
- ✅ Form Validation
- ✅ Error Handling

---

## 📞 Support Tips

1. Read the **IMPLEMENTATION_GUIDE.md** for full documentation
2. Check **controller files** for business logic
3. Review **view files** for HTML structure
4. Look in **CSS files** for styling
5. Study **JavaScript files** for interactions

---

**You're all set! Start by accessing the admin panel or student interface. Good luck! 🎉**
