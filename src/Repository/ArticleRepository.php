<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchByWord($search)
    {

        // je récupére le query builder
        // c'est un objet qui me permet de créer
        // des requêtes SQL, mais en PHP
        $qb = $this->createQueryBuilder('article');

        // j'utilise le constructeur de requête
        // pour faire un select sur la table article
        $query = $qb->select('article')
            // je récupère les article dont le titre
            // correspond à :word
            ->where('article.title LIKE :search')
            // je défini la valeur de :word
            // en lui disant que le mot, peut contenir des
            // caractères avant et après, il sera quand meme trouvé
            // je le fais en deux étapes avec setParametre
            // ça permet à Doctrine et SF de sécuriser
            // la variable $word
            ->setParameter('search', '%'.$search.'%')
            // je récupère la requête générée
            ->getQuery();

        // je l'execute en bdd et je récupère les résultats
        return $query->getResult();
    }


//$query = $qb->select('u')
//    ->where('u.biography LIKE :word')
//    ->setParameter('word', '%'.$word.'%')
//    ->getQuery();

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}