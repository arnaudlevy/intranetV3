<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Utilisateur.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 28/09/2020 08:41

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonException;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
abstract class Utilisateur implements UserInterface, Serializable
{
    public const HOMME = 'M.';
    public const FEMME = 'Mme';

    /**
     * @ORM\Column(type="string", length=75)
     *
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $password = '';

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=75)
     *
     */
    protected $typeUser;

    /**
     * @ORM\Column(type="string", length=75)
     * @Groups({"utilisateur","etudiants_administration"})
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=75)
     * @Groups({"utilisateur","etudiants_administration"})
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"etudiants_administration", "utilisateur"})
     */
    protected $mailUniv;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     */
    protected $siteUniv;

    /**
     * @ORM\Column( type="string", length=255,nullable=true)
     *
     */
    protected $mailPerso;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     */
    protected $sitePerso;

    /**
     * @ORM\Column(name="civilite", type="string", length=3, options={"default":"M."})
     * @Groups({"etudiants_administration"})
     */
    protected string $civilite = 'M.'; //M. ou Mme

    /**
     * @ORM\Column(type="date",nullable=true)
     *
     */
    protected $dateNaissance;

    /**
     * @ORM\Column(type="string", length=20,nullable=true)
     *
     */
    protected $tel1;

    /**
     * @ORM\Column(type="string", length=20,nullable=true)
     *
     */
    protected $tel2;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     */
    protected $remarque;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     */
    protected $signature;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    protected bool $visible = true;

    /**
     * @var Adresse|null
     * @ORM\OneToOne(targetEntity="App\Entity\Adresse", cascade={"persist"})
     */
    private ?Adresse $adresse;


    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $roles = '';

    /**
     * @var DateTime $created
     * @ORM\Column(type="datetime")
     */
    private DateTime $created;

    /**
     * @var DateTime $updated
     * @ORM\Column(type="datetime")
     */
    protected DateTime $updated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $resetToken;

    public function __construct()
    {
        $this->updated = new DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedValue(): void
    {
        $this->created = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedValue(): void
    {
        $this->updated = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * @return mixed
     */
    public function getTypeUser()
    {
        return $this->typeUser;
    }

    /**
     * @param mixed $typeUser
     */
    public function setTypeUser($typeUser): void
    {
        $this->typeUser = $typeUser;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getSiteUniv()
    {
        if ($this->siteUniv !== '' && $this->siteUniv !== null) {
            if (strpos($this->siteUniv, 'http') === 0) {
                return $this->siteUniv;
            }

            return 'https://' . $this->siteUniv;
        }

        return $this->siteUniv;
    }

    /**
     * @param mixed $siteUniv
     */
    public function setSiteUniv($siteUniv): void
    {
        $this->siteUniv = $siteUniv;

    }

    /**
     * @return mixed
     */
    public function getSitePerso()
    {
        if ($this->sitePerso !== '' && $this->sitePerso !== null) {
            if (strpos($this->sitePerso, 'http') === 0) {
                return $this->sitePerso;
            }

            return 'https://' . $this->sitePerso;
        }

        return $this->sitePerso;
    }

    /**
     * @param mixed $sitePerso
     */
    public function setSitePerso($sitePerso): void
    {
        $this->sitePerso = $sitePerso;

    }

    /**
     * @return string|null
     */
    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    /**
     * @return string|null
     */
    public function getCiviliteLong(): ?string
    {
        return $this->civilite === 'M.' ? 'Monsieur' : 'Madame';
    }

    /**
     * @param $civilite
     */
    public function setCivilite($civilite): void
    {
        $this->civilite = $civilite;
    }

    /**
     * @return mixed
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * @param mixed $dateNaissance
     */
    public function setDateNaissance($dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return mixed
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * @param mixed $tel1
     */
    public function setTel1($tel1): void
    {
        $this->tel1 = $tel1;
    }

    /**
     * @return mixed
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * @param mixed $tel2
     */
    public function setTel2($tel2): void
    {
        $this->tel2 = $tel2;
    }

    /**
     * @return mixed
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * @param mixed $remarque
     */
    public function setRemarque($remarque): void
    {
        $this->remarque = $remarque;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible): void
    {
        $this->visible = $visible;
    }

    /**
     * @return Adresse|null
     */
    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    /**
     * @param Adresse|null $adresse
     */
    public function setAdresse(?Adresse $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = json_decode($this->roles, true, 2, JSON_THROW_ON_ERROR);

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @throws JsonException
     */
    public function setRoles(array $roles): void
    {
        $this->roles = json_encode($roles, JSON_THROW_ON_ERROR);
    }

    /**
     * @return string
     */
    public function getDisplayPr(): string
    {
        return ucfirst($this->prenom) . ' ' . mb_strtoupper($this->nom);
    }

    /**
     * @return string
     */
    public function getDisplay(): string
    {
        return mb_strtoupper($this->nom) . ' ' . ucfirst($this->prenom);
    }

    /**
     * @return array
     */
    public function getMails(): array
    {
        $mails = [];
        if ($this->getMailUniv() !== null && trim($this->getMailUniv()) !== '') {
            $mails[] = trim($this->getMailUniv());
        }

        if ($this->getMailPerso() !== null && trim($this->getMailPerso()) !== '') {
            $mails[] = trim($this->getMailPerso());
        }

        return $mails;
    }

    /**
     * @return mixed
     */
    public function getMailUniv()
    {
        return $this->mailUniv;
    }

    /**
     * @param mixed $mailUniv
     */
    public function setMailUniv($mailUniv): void
    {
        $this->mailUniv = $mailUniv;
    }

    /**
     * @return mixed
     */
    public function getMailPerso()
    {
        return $this->mailPerso;
    }

    /**
     * @param mixed $mailPerso
     */
    public function setMailPerso($mailPerso): void
    {
        $this->mailPerso = $mailPerso;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getAvatarInitiales()
    {
        return strtoupper(substr(trim($this->getPrenom()), 0, 1) . '' . substr(trim($this->getNom()), 0, 1));
    }

    public function serialize()
    {
        return serialize($this->getId());
    }

    public function unserialize($serialized)
    {
        $this->uuid = unserialize($serialized);
    }
}
