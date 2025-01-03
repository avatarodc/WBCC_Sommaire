<?php

/**
 * Classe FileManagerCtrl pour la gestion des fichiers
 * Permet de gérer les opérations de base sur les fichiers de manière sécurisée
 */
class DocumentCtrl extends Controller
{
    /** @var array Types MIME autorisés */
    private array $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'application/zip',
        'application/x-rar-compressed',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ];

    /** @var array Extensions autorisées */
    private array $allowedExtensions = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'jpg',
        'jpeg',
        'png',
        'zip',
        'rar',
        'ppt',
        'pptx'
    ];

    /** @var int Taille maximale des fichiers en octets (10MB par défaut) */
    private int $maxFileSize = 10485760;

    /** @var string Répertoire de base pour les uploads */
    private string $baseUploadDir;

    /** @var Database Instance de la base de données */
    private Database $db;

    /**
     * Constructeur
     * @throws Exception Si le répertoire d'upload ne peut pas être créé
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getDB();
        $this->baseUploadDir = dirname(__DIR__) . '/uploads/';
        $this->initializeUploadDirectory();
    }

    /**
     * Configure les types MIME autorisés
     * @param array $types Liste des types MIME
     * @return void
     */
    public function setAllowedMimeTypes(array $types): void
    {
        $this->allowedMimeTypes = $types;
    }

    /**
     * Configure les extensions autorisées
     * @param array $extensions Liste des extensions
     * @return void
     */
    public function setAllowedExtensions(array $extensions): void
    {
        $this->allowedExtensions = array_map('strtolower', $extensions);
    }

    /**
     * Configure la taille maximale des fichiers
     * @param int $size Taille en octets
     * @return void
     */
    public function setMaxFileSize(int $size): void
    {
        $this->maxFileSize = $size;
    }

    /**
     * Configure le répertoire de base pour les uploads
     * @param string $dir Chemin du répertoire
     * @return void
     * @throws Exception Si le répertoire ne peut pas être créé
     */
    public function setBaseUploadDir(string $dir): void
    {
        $this->baseUploadDir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->initializeUploadDirectory();
    }

    /**
     * Upload un fichier
     * @param array $file Fichier $_FILES
     * @param string $subDir Sous-répertoire optionnel
     * @param string|null $customFilename Nom de fichier personnalisé optionnel
     * @return array Informations sur le fichier uploadé
     * @throws Exception
     */
    public function uploadFile(array $file, string $subDir = '', ?string $customFilename = null): array
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Fichier invalide ou attaque potentielle');
        }

        try {
            $this->validateFile($file);

            $uploadDir = $this->getUploadPath($subDir);
            $filename = $this->generateFileName($file, $customFilename);
            $uploadPath = $uploadDir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Erreur lors du téléchargement du fichier');
            }

            // Vérification supplémentaire après upload
            if (!$this->isValidFile($uploadPath)) {
                unlink($uploadPath);
                throw new Exception('Le fichier uploadé est invalide ou potentiellement dangereux');
            }

            chmod($uploadPath, 0644);

            return [
                'success' => true,
                'originalName' => htmlspecialchars($file['name']),
                'fileName' => $filename,
                'filePath' => $uploadPath,
                'fileSize' => $file['size'],
                'mimeType' => $file['type'],
                'extension' => pathinfo($filename, PATHINFO_EXTENSION)
            ];
        } catch (Exception $e) {
            if (isset($uploadPath) && file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw $e;
        }
    }

    /**
     * Déplace un fichier de manière sécurisée
     * @param string $sourcePath Chemin source
     * @param string $destinationPath Chemin de destination
     * @return bool Succès de l'opération
     * @throws Exception Si les chemins sont invalides
     */
    public function moveFile(string $sourcePath, string $destinationPath): bool
    {
        if (!$this->isPathInUploadDir($sourcePath) || !$this->isPathInUploadDir($destinationPath)) {
            throw new Exception('Chemins non autorisés');
        }

        if (!file_exists($sourcePath)) {
            return false;
        }

        $destDir = dirname($destinationPath);
        if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
            throw new Exception("Impossible de créer le répertoire de destination");
        }

        return rename($sourcePath, $destinationPath);
    }

    /**
     * Supprime un fichier de manière sécurisée
     * @param string $filePath Chemin du fichier
     * @return bool Succès de l'opération
     * @throws Exception Si le chemin est invalide
     */
    public function deleteFile(string $filePath): bool
    {
        if (!$this->isPathInUploadDir($filePath)) {
            throw new Exception('Chemin non autorisé');
        }

        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Vérifie si un fichier existe de manière sécurisée
     * @param string $filePath Chemin du fichier
     * @return bool
     * @throws Exception Si le chemin est invalide
     */
    public function fileExists(string $filePath): bool
    {
        if (!$this->isPathInUploadDir($filePath)) {
            throw new Exception('Chemin non autorisé');
        }

        return file_exists($filePath);
    }

    /**
     * Valide un fichier
     * @param array $file Fichier à valider
     * @throws Exception si le fichier est invalide
     */
    private function validateFile(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('Le fichier est trop volumineux (max ' . $this->formatSize($this->maxFileSize) . ')');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw new Exception('Extension de fichier non autorisée');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            throw new Exception('Type de fichier non autorisé');
        }
    }

    /**
     * Vérifie si un chemin est dans le répertoire d'upload
     * @param string $path Chemin à vérifier 
     * @return bool
     */
    private function isPathInUploadDir(string $path): bool
    {
        $realPath = realpath($path);
        $uploadDir = realpath($this->baseUploadDir);

        return $realPath !== false &&
            $uploadDir !== false &&
            strpos($realPath, $uploadDir) === 0;
    }

    /**
     * Vérifie si un fichier est valide après upload
     * @param string $filePath Chemin du fichier
     * @return bool
     */
    private function isValidFile(string $filePath): bool
    {
        // Vérifications supplémentaires post-upload
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return in_array($mimeType, $this->allowedMimeTypes, true) &&
            in_array($extension, $this->allowedExtensions, true);
    }

    /**
     * Génère le chemin d'upload complet
     * @param string $subDir Sous-répertoire
     * @return string
     * @throws Exception Si le répertoire ne peut pas être créé
     */
    private function getUploadPath(string $subDir): string
    {
        $uploadDir = $this->baseUploadDir;
        if (!empty($subDir)) {
            $uploadDir .= trim($subDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new Exception("Impossible de créer le répertoire d'upload");
        }

        return $uploadDir;
    }

    /**
     * Génère un nom de fichier unique et sécurisé
     * @param array $file Fichier original
     * @param string|null $customFilename Nom personnalisé
     * @return string
     */
    private function generateFileName(array $file, ?string $customFilename = null): string
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($customFilename !== null) {
            $basename = $customFilename;
        } else {
            $basename = pathinfo($file['name'], PATHINFO_FILENAME);
            $basename = $this->sanitizeFileName($basename);
            $basename .= '_' . bin2hex(random_bytes(8));
        }

        return $basename . '.' . $extension;
    }

    /**
     * Nettoie un nom de fichier
     * @param string $filename Nom de fichier à nettoyer
     * @return string
     */
    private function sanitizeFileName(string $filename): string
    {
        $filename = preg_replace('/[^a-zA-Z0-9-_.]/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        return substr($filename, 0, 100);
    }

    /**
     * Initialise le répertoire d'upload
     * @throws Exception Si le répertoire ne peut pas être créé
     */
    private function initializeUploadDirectory(): void
    {
        if (!is_dir($this->baseUploadDir) && !mkdir($this->baseUploadDir, 0755, true)) {
            throw new Exception("Impossible de créer le répertoire d'upload");
        }
    }

    /**
     * Retourne le message d'erreur pour un code d'erreur d'upload
     * @param int $code Code d'erreur
     * @return string
     */
    private function getUploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale autorisée par PHP',
            UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale autorisée par le formulaire',
            UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement téléchargé',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été téléchargé',
            UPLOAD_ERR_NO_TMP_DIR => 'Le dossier temporaire est manquant',
            UPLOAD_ERR_CANT_WRITE => 'Échec de l\'écriture du fichier sur le disque',
            UPLOAD_ERR_EXTENSION => 'Une extension PHP a arrêté le téléchargement du fichier',
            default => 'Une erreur inconnue s\'est produite',
        };
    }

    /**
     * Formate une taille en bytes de manière lisible
     * @param int $bytes Taille en bytes
     * @return string
     */
    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
