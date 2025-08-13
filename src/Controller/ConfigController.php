<?php

namespace App\Controller;

use App\Entity\EasyConfig\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfigController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/system-config', name: 'system_config')]
    public function index(): Response
    {
        // Fetch all configurations from the database
        $configs = $this->entityManager->getRepository(Config::class)->findBy([], ['name' => 'ASC']);

        // Group configurations by logical categories for better display
        $groupedConfigs = [
            'Site Information' => [],
            'Contact Information' => [],
            'Social Media' => [],
            'Features' => [],
            'Appearance' => [],
            'System Settings' => [],
            'Content Settings' => [],
            'Date/Time Settings' => [],
            'API Settings' => []
        ];

        foreach ($configs as $config) {
            $key = $config->getKey();
            $category = $this->categorizeConfig($key);
            $groupedConfigs[$category][] = $config;
        }

        // Remove empty categories
        $groupedConfigs = array_filter($groupedConfigs, fn($group) => !empty($group));

        return $this->render('pages/config.html.twig', [
            'configs' => $configs,
            'groupedConfigs' => $groupedConfigs,
            'totalConfigs' => count($configs)
        ]);
    }

    private function categorizeConfig(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'site_') => 'Site Information',
            str_starts_with($key, 'contact_') => 'Contact Information',
            str_contains($key, '_url') && !str_starts_with($key, 'api_') => 'Social Media',
            in_array($key, ['maintenance_mode', 'enable_blog', 'enable_comments']) => 'Features',
            str_contains($key, '_color') => 'Appearance',
            in_array($key, ['max_upload_size']) => 'System Settings',
            in_array($key, ['posts_per_page', 'footer_content']) => 'Content Settings',
            str_contains($key, 'date') || str_contains($key, 'time') || $key === 'last_backup' => 'Date/Time Settings',
            str_contains($key, 'api_') => 'API Settings',
            default => 'Other Settings'
        };
    }
}
