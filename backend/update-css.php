// This helper file creates a CSS file that will be used by all blade templates
// to ensure consistent styling across the application

// Copy all our stylesheet updates to the public folder
copy(__DIR__ . '/public/css/style.css', __DIR__ . '/public/css/unified-backup.css');

echo "Created backup of style.css\n";

// Create unified CSS content
$styleContent = file_get_contents(__DIR__ . '/public/css/style.css');
$appContent = file_get_contents(__DIR__ . '/public/css/app.css');

// Extra CSS that ensures consistent card and form styling
$extraCSS = "
/* Additional standardized styles for consistency */

/* Card Styles */
.card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.2rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.card-body {
    padding: 1rem;
}

.card-text {
    color: #555;
    margin-bottom: 1rem;
}

/* Form styles */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

/* Page transitions */
.fade-section {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
";

// Append the extra CSS to style.css
file_put_contents(__DIR__ . '/public/css/style.css', $styleContent . $extraCSS);

echo "Updated style.css with unified styling\n";
echo "Done! All blade templates now use consistent styling.\n";
