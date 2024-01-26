<?php

declare(strict_types=1);

namespace App\Services\AltGenerator;

use Adeliom\EasyMediaBundle\Entity\Media;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;

#[AsAlias(id: 'gptAltGenerator', public: true)]
class GptAltGenerator implements AltGeneratorInterface
{
    private \OpenAI\Client $client;
    private string $prompt = <<<PROMPT
Decris moi cette image pour qu\'elle soit intégrée sur un site. 
Ta réponse doit être courte, descriptive, et ne doit contenir qu\'une phrase';
PROMPT;

    public function __construct(
        #[Autowire(env: 'OPENAI_KEY')] $openAIKey,
    ) {
        $this->client = \OpenAI::client($openAIKey);
    }

    public function generate(Media $entity, null|string|File $source): string
    {
        if ($source instanceof File) {
            $file_contents = file_get_contents($source->getPathname());

            if ($file_contents) {
                $url = 'data:image/jpeg;base64,' . base64_encode($file_contents);
            }
        } else {
            $url = $source;

            if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
                $url = null;
            }
        }

        if (str_contains($url, '.ddev.site')) {
            // handle local images
            $file_contents = file_get_contents($url);

            if ($file_contents) {
                $url = 'data:image/jpeg;base64,' . base64_encode($file_contents);
            }
        }

        if (null !== $url) {
            $result = $this->client->chat()->create([
                'model' => 'gpt-4-vision-preview',
                'messages' => [
                    [
                        "role" => "system",
                        "content" => "You are a expert SEO assistant."
                    ],
                    [
                        "role" => "user",
                        "content" => [
                            [
                                "type" => "text",
                                "text" => $this->prompt,
                            ],
                            [
                                "type" => "image_url",
                                "image_url" => [
                                    "url" => $url,
                                ]
                            ],
                        ],
                    ],
                ],
                'temperature' => 1.0,
                'max_tokens' => 30,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ]);

            return $result->choices[0]->message->content;
        }

        return '';
    }
}
