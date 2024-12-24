<?php
require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// SECURITY RISK: Upload directory is publicly accessible
$uploadDir = __DIR__ . '/../uploads/';  // Physical path
$webAccessPath = '/uploads/';  // Web-accessible path
$message = '';

// CRITICAL VULNERABILITY: Directory Traversal
// This code allows attackers to read any file on the system by manipulating the 'file' parameter
if (isset($_GET['file'])) {
    $requestedFile = $_GET['file'];
    // SECURITY RISK: No path sanitization or validation
    // Attacker can use '../' to access files outside the intended directory
    readfile($requestedFile);
    exit;
}

// Vulnerable file upload handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFile = $uploadDir . $fileName;
    $uploadError = $_FILES["fileToUpload"]["error"];
    
    // VULNERABILITY: Weak File Extension Validation
    // 1. Uses strpos() which can be bypassed (e.g., 'malicious.php.jpg')
    // 2. Doesn't check actual file content/MIME type
    $allowedExtensions = array('.pdf', '.doc', '.docx', '.jpg', '.png');
    $isAllowedFile = false;
    foreach ($allowedExtensions as $ext) {
        if (strpos(strtolower($fileName), $ext) !== false) {
            $isAllowedFile = true;
            break;
        }
    }
    
    if (!$isAllowedFile) {
        $message = "Sadece PDF, DOC, DOCX, JPG ve PNG dosyaları kabul edilmektedir.";
        error_log("Upload failed - Invalid file type - File: " . $fileName);
    } else if ($uploadError !== UPLOAD_ERR_OK) {
        // Log and handle specific upload errors
        switch ($uploadError) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "Dosya boyutu PHP'nin izin verdiği maksimum boyutu aşıyor.";
                error_log("Upload failed - File too large (PHP INI limit) - File: " . $fileName);
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "Dosya boyutu form limitini aşıyor.";
                error_log("Upload failed - File too large (Form limit) - File: " . $fileName);
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "Dosya sadece kısmen yüklendi.";
                error_log("Upload failed - Partial upload - File: " . $fileName);
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "Hiçbir dosya yüklenmedi.";
                error_log("Upload failed - No file uploaded");
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Geçici klasör eksik.";
                error_log("Upload failed - Missing temporary folder");
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Dosya diske yazılamadı.";
                error_log("Upload failed - Failed to write to disk - File: " . $fileName);
                break;
            default:
                $message = "Bilinmeyen bir hata oluştu.";
                error_log("Upload failed - Unknown error ({$uploadError}) - File: " . $fileName);
                break;
        }
    } else {
        // SECURITY RISK: Race Condition Possible
        // Time between checks and actual file operations could allow for exploitation
        if (!file_exists($uploadDir)) {
            error_log("Upload failed - Directory does not exist: " . $uploadDir);
            $message = "Sistem yapılandırma hatası. Lütfen daha sonra tekrar deneyin.";
        } elseif (!is_writable($uploadDir)) {
            error_log("Upload failed - Directory not writable: " . $uploadDir);
            $message = "Sistem yapılandırma hatası. Lütfen daha sonra tekrar deneyin.";
        } else {
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $message = "Belgeniz başarıyla yüklendi. Destek ekibimiz en kısa sürede sizinle iletişime geçecektir.";
                error_log("Upload successful - File: " . $fileName . " saved to " . $targetFile);
            } else {
                $message = "Belge yüklenirken bir hata oluştu. Lütfen tekrar deneyiniz.";
                error_log("Upload failed - move_uploaded_file failed - File: " . $fileName . " Target: " . $targetFile);
                
                // Additional error information
                $errorDetails = error_get_last();
                if ($errorDetails) {
                    error_log("PHP Error: " . json_encode($errorDetails));
                }
            }
        }
    }
}

// SECURITY RISK: Information Disclosure
// Displays all uploaded files without access control
$uploadedFiles = [];
if (file_exists($uploadDir)) {
    $uploadedFiles = array_diff(scandir($uploadDir), array('.', '..'));
}
?>

<div class="help-portal-container">
    <div class="portal-header">
        <h1>Müşteri Destek Portalı</h1>
        <p class="portal-description">
            Kripto para işlemlerinizle ilgili yardıma mı ihtiyacınız var? 
            Belgelerinizi güvenli bir şekilde yükleyin, uzman ekibimiz size yardımcı olsun.
        </p>
    </div>

    <?php if ($message): ?>
        <div class="alert <?php echo strpos($message, 'hata') !== false ? 'alert-error' : 'alert-success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="support-grid">
        <div class="upload-section">
            <div class="upload-container">
                <h2>Belge Yükleme</h2>
                <div class="upload-instructions">
                    <p>Kabul edilen dosya türleri:</p>
                    <ul>
                        <li>PDF dökümanları</li>
                        <li>Word dökümanları (.doc, .docx)</li>
                        <li>Resim dosyaları (.jpg, .png)</li>
                    </ul>
                    <p>Maksimum dosya boyutu: 10MB</p>
                </div>
                
                <form action="/?page=support" method="post" enctype="multipart/form-data">
                    <div class="file-upload-wrapper">
                        <input type="file" name="fileToUpload" id="fileToUpload" class="file-input" required>
                        <label for="fileToUpload" class="file-label">
                            <span class="file-icon">📎</span>
                            <span class="file-text">Dosya Seçin veya Sürükleyin</span>
                        </label>
                        <div id="file-name" class="file-name"></div>
                    </div>
                    <button type="submit" class="upload-button">
                        <span class="button-icon">⬆️</span> Dosyayı Yükle
                    </button>
                </form>
            </div>
        </div>

        <!-- Replace the existing help-section div with this enhanced version -->
<div class="help-section">
    <h2>Nasıl Yardımcı Olabiliriz?</h2>
    <div class="help-cards">
        <div class="help-card">
            <div class="card-icon">💼</div>
            <h3>Hesap İşlemleri</h3>
            <p>Hesap açma, doğrulama ve güvenlik ile ilgili belgelerinizi yükleyin.</p>
            <div class="card-details">
                <ul>
                    <li>Kimlik doğrulama belgeleri</li>
                    <li>Adres kanıtı</li>
                    <li>Hesap bildirimleri</li>
                </ul>
            </div>
        </div>
        
        <div class="help-card">
            <div class="card-icon">💱</div>
            <h3>İşlem Sorunları</h3>
            <p>Bekleyen veya başarısız işlemlerinizle ilgili kanıtları paylaşın.</p>
            <div class="card-details">
                <ul>
                    <li>İşlem makbuzları</li>
                    <li>Hata bildirimleri</li>
                    <li>Banka dekontları</li>
                </ul>
            </div>
        </div>
        
        <div class="help-card">
            <div class="card-icon">🔒</div>
            <h3>Güvenlik</h3>
            <p>Şüpheli işlem bildirimleri ve güvenlik endişeleriniz için belge yükleyin.</p>
            <div class="card-details">
                <ul>
                    <li>Şüpheli işlem kanıtları</li>
                    <li>Güvenlik ihlal raporları</li>
                    <li>2FA sorunları</li>
                </ul>
            </div>
        </div>

        <div class="help-card">
            <div class="card-icon">📋</div>
            <h3>Genel Destek</h3>
            <p>Diğer tüm konularla ilgili destek talepleriniz için belge yükleyin.</p>
            <div class="card-details">
                <ul>
                    <li>Platform sorunları</li>
                    <li>Özel talepler</li>
                    <li>Öneri ve şikayetler</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
/* Add these new styles to your existing CSS */
.help-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.help-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.help-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.card-icon {
    font-size: 2.5em;
    margin-bottom: 15px;
    color: #006fe6;
}

.help-card h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.3em;
}

.help-card p {
    color: #666;
    font-size: 0.95em;
    line-height: 1.5;
    margin-bottom: 20px;
}

.card-details {
    margin-top: auto;
    text-align: left;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.card-details ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.card-details li {
    color: #555;
    font-size: 0.9em;
    padding: 5px 0;
    display: flex;
    align-items: center;
}

.card-details li:before {
    content: "•";
    color: #006fe6;
    font-weight: bold;
    margin-right: 8px;
}

@media (max-width: 768px) {
    .help-cards {
        grid-template-columns: 1fr;
    }
    
    .help-card {
        margin-bottom: 20px;
    }
}

/* Update support-grid to be more responsive */
.support-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
    margin-top: 40px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

@media (min-width: 992px) {
    .support-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<style>
.help-portal-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.portal-header {
    text-align: center;
    margin-bottom: 50px;
}

.portal-header h1 {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 20px;
}

.portal-description {
    color: #666;
    font-size: 1.1em;
    max-width: 800px;
    margin: 0 auto;
}

.support-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 40px;
}

.upload-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.upload-instructions {
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.upload-instructions ul {
    margin: 10px 0;
    padding-left: 20px;
}

.file-upload-wrapper {
    margin: 30px 0;
    position: relative;
}

.file-input {
    display: none;
}

.file-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 30px;
    background: #f8f9fa;
    border: 2px dashed #ccc;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-label:hover {
    background: #e9ecef;
    border-color: #006fe6;
}

.file-icon {
    font-size: 2em;
    margin-bottom: 10px;
}

.file-name {
    margin-top: 10px;
    text-align: center;
    color: #006fe6;
    font-size: 0.9em;
}

.upload-button {
    width: 100%;
    padding: 15px;
    background: #006fe6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    cursor: pointer;
    transition: background 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.upload-button:hover {
    background: #005bb5;
}

.help-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.help-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    text-align: center;
    transition: transform 0.3s ease;
}

.help-card:hover {
    transform: translateY(-5px);
}

.card-icon {
    font-size: 2.5em;
    margin-bottom: 15px;
}

.help-card h3 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.help-card p {
    color: #666;
    font-size: 0.9em;
    line-height: 1.5;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    text-align: center;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .support-grid {
        grid-template-columns: 1fr;
    }
    
    .help-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.getElementById('fileToUpload').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    const fileText = document.querySelector('.file-text');
    
    if (fileName) {
        fileNameDisplay.textContent = `Seçilen dosya: ${fileName}`;
        fileText.textContent = 'Dosya Seçildi';
    } else {
        fileNameDisplay.textContent = '';
        fileText.textContent = 'Dosya Seçin veya Sürükleyin';
    }
});
</script> 