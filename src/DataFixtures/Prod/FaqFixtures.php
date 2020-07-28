<?php

namespace App\DataFixtures\Prod;

use App\Entity\Faq;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FaqFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{

    const FAQ = [
        'Combien d’entretiens puis-je passer ?' =>
            ['Une fois sélectionné.e, nous vous présenterons à un maximum d\'entreprises 
            dans le but de matcher avec l\'une d\'entre elles. Nous vous encourageons vivement à postuler également 
            de votre côté. Cela est complémentaire et va maximiser vos chances de décrocher une 
            promesse d\'embauche.', 0],
        'Comment est financée ma formation bootcamp ?' =>
            ['Le bootcamp est une Préparation Opérationnelle à l’Emploi Individuel. 
            Le financement du bootcamp est assuré :
            <br/>- en partie par Pôle Emploi
            <br/>- selon la convention collective de votre entreprise, l’autre partie est prise en charge par :
            <br/>- l’OPCO de votre entreprise
            <br/>- votre entreprise', 1],
        'Suis-je rémunéré.e pendant le bootcamp ?' =>
            [' 1 - Si vous bénéficiez d’ARE lors de votre entrée en formation :
            <br/>- et que vos droits vont jusqu’à la fin du bootcamp, vous touchez vos ARE pendant toute la durée du 
            bootcamp
            <br/>- et que vos droits s’arrêtent pendant le bootcamp, ils ne peuvent pas être prolongés : vous toucherez 
            l’intégralité de vos droits pendant la période prévue, puis vous pourrez bénéficier de la RFPE.
            <br/><br/>2 - Si vous ne bénéficiez pas d’ARE à l’entrée en formation, 
            <br/>- vous pouvez bénéficier de la RFPE pour toute la durée de formation, 
            <br/>- vous pouvez si vous y êtes éligible bénéficier du RSA et/ou de l’ASS,
            <br/>Attention, vos droits RSA sont cumulables avec la RFPE, mais pas vos droits ASS. Renseignez-vous 
            auprès de votre CAF ou de votre conseiller Pôle Emploi pour connaître vos droits.
            <br/><br/>Si vous êtes auto-entrepreneur, vous bénéficiez de la RFPE si vous êtes bien inscrit.e comme 
            demandeur d’emploi (comme c’était nécessaire pour l’entrée en formation) et si votre statut de créateur 
            d’entreprise n’est pas considéré comme votre activité principale.
            <br/><br/>Vous pouvez selon votre situation être éligible à d’autres aides financières comme l’aide à la 
            mobilité ou encore l’aide à l’hébergement. Nous vous invitons à vous rapprocher de votre conseiller Pôle 
            Emploi afin de déterminer toutes les aides dont vous pouvez bénéficier. Attention, il faut faire votre 
            demande dans les 30 jours après le début de la formation. 
            <br/><br/>Assurez-vous de télécharger tous les documents nécessaires sur votre espace de demandeur 
            d’emploi dès le début de la formation afin de ne pas retarder vos indemnisations : RIB à jour, numéro 
            de sécurité sociale, toutes les attestations employeur dont vous disposez.', 1],
        'Que dois-je renseigner lors de mon actualisation sur le site de Pôle Emploi pendant le bootcamp ?' =>
            ['Pour la première actualisation, indiquez que vous êtes toujours en recherche d’emploi, que vous n’avez 
            pas travaillé et que vous n’avez pas suivi de formation en attendant la codification des dossiers de 
            formation Pôle Emploi. Tant que la question de la recherche d’emploi apparaît lors de l’actualisation, 
            continuez à indiquer que vous êtes en recherche d’emploi afin de ne pas être radié.e par PE.', 1],
        'Que se passe-t-il si je suis absent pendant le bootcamp?' =>
            ['Vous êtes tenu.e d’être présent.e et de participer tous les jours pendant le bootcamp. Vous signerez 
            une feuille de présence chaque demi-journée qui sera remise à Pôle Emploi et vous permettra de toucher 
            l’intégralité de votre rémunération de formation. Toute absence devra être justifiée par un justificatif 
            officiel (certificat médical, absence pour un événement avec votre entreprise justifiée par votre tuteur, 
            etc.). En cas d’absence injustifiée, vous serez convoqué par l’équipe pédagogique pour vous expliquer. 
            Après 2 absences injustifiées, votre entreprise d’accueil sera prévenue et il sera décidé avec elle si 
            vous pouvez continuer la formation. 3 absences injustifiées signifient votre renvoi de la formation. ', 1],
        'Quel sera mon statut pendant le bootcamp ?' =>
            ['Vous avez un statut de demandeur d’emploi, vous pouvez donc toucher des aides financières comme les 
            ARE, le RSA etc. De plus vous pouvez bénéficier de certaines réductions notamment pour les transports 
            (cf abonnements Solidaires TCL).', 1],
        'En cas de retard de paiement par Pôle Emploi, puis-je récupérer les sommes non-perçues?' =>
            ['Il arrive régulièrement que Pôle Emploi ait du retard dans le traitement des dossiers et que les 
            sommes ne soient pas débloquées à temps. Les sommes dues sont toujours rattrapées et payées. Dans ce 
            cas, il faut se réinscrire à Pôle Emploi à la fin du bootcamp et s’actualiser comme étant salarié.e en 
            poste jusqu’à avoir touché les sommes non perçues.', 1],
        'Puis-je avoir le planning des 12 semaines de bootcamp en avance pour m’organiser ?' =>
            ['Le manque de visibilité sur le planning précis est une nécessité pour permettre le bon déroulement de la 
            pédagogie. Planifiez vos 12 semaines de bootcamp de manière à être disponible pour la formation de 
            9h15 à 18h.
            <br/><br/>NB: il y aura une à deux semaines de bootcamp dite en “remote” (= à distance) dont les dates 
            vous seront communiquées à l’avance, et durant laquelle vous serez autonome, c’est-à-dire qu’il sera 
            possible de ne pas être physiquement présent sur Lyon. Durant cette période, des activités seront à 
            réaliser et contrôlé par l’équipe pédagogique.', 1],
        'Comment faire si j’ai un rendez-vous ou un impératif ponctuel sur les plages horaires du bootcamp ?' =>
            ['Privilégiez la prise de rendez-vous en dehors des horaires du bootcamp. Si vous avez un impératif lors 
            d’un jour de cours en présentiel, vous êtes tenu.e de vous rapprocher de l’équipe pédagogique pour les 
            informer et étudier avec eux l’impact sur votre formation.', 1],
        'Le bootcamp est-il certifiant ou diplômant ?' =>
            ['Le bootcamp n’est ni certifiant ni diplômant. Vous aurez cependant l’occasion de passer plusieurs 
            certifications externes durant le bootcamp (Google Marketing Digital notamment). Si vous êtes en 
            alternance à l’issue du bootcamp, nous vous accompagnons à passer un diplôme de niveau Bac+3 reconnu par 
            l’Etat (RNCP).', 1],
        'Votre contrat de travail' =>
            ['Si vous êtes en CDI : vous êtes autonomes pour la mise en place de votre contrat de travail. Les 
            questions qui suivent s’adressent majoritairement aux apprenants en contrat de professionnalisation.', 2],
        'Sous quel délai mon contrat de travail doit-il démarrer après la fin de la POEI ?' =>
            ['Pour une personne en CDI : la règle est de commencer le contrat au lendemain de la fin du bootcamp. 
            Néanmoins, si des contraintes entreprise (notamment rythme d’intégration mensuel par exemple) décalent 
            cette rentrée, quelques semaines de battement peuvent être tolérées au cas par cas.
            <br/><br/>Pour une personne en CDD en contrat de professionnalisation : le contrat doit commencer au 
            plus tard le premier jour de cours en alternance. Il doit se finir au plus tôt le dernier jour de 
            cours et 12 mois après le premier jour du contrat de professionnalisation.', 2],
        'Quand aurai-je mon contrat de travail ?' =>
            ['Si vous êtes en alternance : tous les éléments sont envoyés à vos entreprises pour qu\'ils puissent 
            faire les demandes de financement et établir vos contrats au plus tard un mois avant la fin du bootcamp. 
            Vous pouvez donc prendre contact avec elles à partir de ce moment là pour donner des nouvelles et fixer 
            une date de signature de contrat.
            <br/><br/>N.B :  vos entreprises ont jusqu\'à 4 jours après votre arrivée pour vous faire signer le 
            contrat. Ne soyez pas inquiets si elles ne vous proposent pas de venir signer votre contrat avant votre 
            arrivée. Par contre, si 3 jours après votre arrivée vous n\'avez toujours pas signé votre contrat, il 
            faut impérativement nous remonter l\'information.', 2],
        'Quelle est la forme de mon contrat de travail ?' =>
            ['C’est un CDD de 12 mois en contrat de professionnalisation.', 2],
        'Quel salaire vais-je percevoir pendant mon année de contrat de professionnalisation ?' =>
            ['Les salaires minimaux en contrat de professionnalisation sont définis légalement au niveau de la 
            branche d\'activité de l\'entreprise. 
            <br/><br/>Les salaires de contrat de professionnalisation sont définis selon 2 critères :
            <br/>- Le niveau de diplôme à l\'entrée en formation
            <br/>- L\'âge (plus ou moins de 26 ans).
            <br/><br/>Les salaires varient entre 80% et 120% du SMIC selon ces critères.
            <br/><br/>Vous trouverez ci-dessous la grille des rémunérations 2020 pour les entreprises qui dépendent 
            de la Convention Collective SYNTEC. Pour connaître la convention collective de rattachement de votre 
            entreprise, vous pouvez vous rapprocher d’elle directement. L’information sur les salaires plancher est 
            ensuite disponible sur internet. Attention, cette grille de rémunération indique des salaires bruts. 
            Pour un contrat de professionnalisation, les salaires ne sont pas exonérés de charges sociales.
            <br/><br/>La grille de salaire se lit ainsi : 
            <br/>En lignes :
            <br/>- niveau de formation à l’entrée : votre niveau de diplôme à l’entrée en alternance (3 et 4 
            correspondant à un niveau Bac, 7 correspondant à un niveau Bac+5)
            <br/>- alternance en 1 an donc ligne “1ère année”
            <br/>En colonne :
            <br/>-choisir parmi les deux dernières colonnes celle qui correspond à votre âge (plus ou moins de 26 ans)
            <br/>Comme vous allez exercer un métier de challenges et d\'objectifs, nous vous conseillons, lorsque vous 
            aurez fait vos preuves et obtenu vos premiers résultats chiffrés, d\'échanger avec votre entreprise sur 
            la possibilité de mettre en place des primes ou rémunérations variables en fonction de la réalisation 
            d\'objectifs. Le défi est de réussir à mener cette négociation subtilement et efficacement avec votre 
            entreprise.', 2],
        'Est-ce que je bénéficie d’une mutuelle d’entreprise pendant mon année d’alternance ?' =>
            ['En tant que titulaire d’un contrat de professionnalisation, vous êtes un.e salarié.e à part 
            entière de l\'entreprise. Vous avez les mêmes droits et devoirs que tout autre salarié. Vous avez 
            le droit à une mutuelle, une prévoyance, et si l\'entreprise donne accès à d\'autres avantages comme 
            les titres-restaurant par exemple, vous y avez aussi accès', 2],
        'Quel complément de salaire Pôle Emploi peut-il me fournir si j’ai encore des droits et que le montant de mon 
        salaire en contrat de professionnalisation est inférieur au montant des ARE ?' =>
            ['Si votre salaire en contrat de professionnalisation est inférieur à ce que vous touchiez en tant que 
            demandeur d\'emploi, Pôle Emploi vous versera un complément pour la différence (simulateur disponible 
            ici).
            <br/><br/>Si vous êtes dans ce cas, il faut vous réinscrire chez Pôle Emploi à la fin de la POEI. 
            Vous devrez ensuite vous actualiser tous les mois comme demandeur d’emploi, indiquer vos heures 
            travaillées ainsi que votre estimation de salaire, et transmettre votre fiche de paie via votre espace 
            Pôle Emploi pour pouvoir toucher le complément.', 2],
        'Est-il possible de percevoir des compléments de salaire comme la prime d’activité ou une autre prime ?' =>
            ['Votre éligibilité à d’éventuelles primes d\'activités doit être étudiée au cas par cas; renseignez-vous 
            auprès de votre conseiller en fonction du dispositif que vous ciblez.', 2],
        'Le détail de mes missions sera-t-il spécifié dans mon contrat de travail ?' =>
            ['Le contrat de professionnalisation est formalisé via un CERFA et il n\'est pas obligatoire d\'y faire 
            figurer une fiche de missions. Cependant, rien ne l\'empêche donc vous pouvez tout à fait proposer à 
            votre entreprise de définir avec elle une fiche de poste et de l\'ajouter en annexe au contrat.', 2],
        'Est-il possible de refuser son contrat de travail ?' =>
            ['Non, sauf cas vraiment spécifique qui sera discuté individuellement entre vous, l\'école, Pôle Emploi 
            et l\'entreprise. Pôle Emploi et votre entreprise d’accueil font un effort financier important pour votre 
            formation à Rocket School. De plus, grâce à l\'engagement de l\'entreprise (qui vous a attendu 3 mois), 
            vous avez pu bénéficier d\'une formation gratuite, le respect de votre engagement est donc primordial.', 2],
        'Aurai-je droit à des congés payés?' =>
            ['Votre contrat de professionnalisation est un contrat de travail, vous bénéficiez donc de 5 semaines de 
            congés payés par an. Nous vous encourageons à les poser pendant la période de congés pédagogiques indiqués 
            sur le calendrier de l’alternance, pendant laquelle il n’y aura pas de cours prévus le vendredi. Si vous 
            souhaitez toutefois les poser hors de cette période, les jours de cours manqués devront obligatoirement 
            être rattrapés. Vos demandes de congés doivent être acceptées par votre entreprise.
            <br/>NB : pendant les congés pédagogiques vous êtes tenus d’être en entreprise si vous ne posez pas 
            de congés.', 3],
        'Si je pose une semaine de congés, combien de jours dois-je poser ?' =>
            ['En principe 5 jours, mais nous vous conseillons de vous rapprocher de votre entreprise pour vérifier 
            quel système de congés elle applique.', 3],
        'Si je ne peux pas être présent.e un jour de cours, dois-je le rattraper?' =>
            ['Dès que vous connaissez la date de votre absence, vous devez impérativement prévenir l’équipe pédagogique.
            <br/>Toute absence devra être rattrapée qu\'elle soit justifiée ou non, et que vous présentiez le diplôme 
            ou non.. La journée de cours du vendredi est considérée comme un jour de travail. Pour une absence 
            prévenue et justifiée, une date de rattrapage vous sera proposée ainsi qu’à votre entreprise. Pour une 
            absence non justifiée, une date de rattrapage sera également prévue et les sanctions éventuelles de 
            l’absence seront mises en place par votre entreprise comme un salarié normal. ', 3],
        'S’il n’y a pas cours un vendredi (pont, congé pédagogique etc.), que dois-je faire?' =>
            ['Votre rythme hebdomadaire normal est de 35 heures payées par votre entreprise, dont une journée de 
            cours à la Rocket School. Si vous n\'avez pas cours, vous devez être en entreprise. Sinon, il faut 
            poser une journée.', 3],
        'Si je suis en alternance, dois-je obligatoirement présenter le diplôme ?' =>
            ['Il n’est pas obligatoire de passer le diplôme, mais nous vous le conseillons fortement. En effet, votre 
            alternance à la Rocket School vous permettra d’obtenir un diplôme de niveau licence en seulement 1 an : 
            c\'est un atout précieux pour votre avenir professionnel. Votre présence les jours de cours est obligatoire 
            que vous passiez le diplôme ou non.', 3],
        'Si je ne présente pas le diplôme, dois-je être présent.e sur le campus pendant la semaine d’examens ?' =>
            ['Oui, tous les jours indiqués comme "école" ou “examen” impliquent une présence sur le campus. Si vous ne 
            présentez pas le diplôme, des cours seront organisés pendant les examens.', 3],
        'Est-il possible de faire évoluer mon contrat de travail au cours de l’année ?' =>
            ['Si votre entreprise souhaite modifier votre contrat pour passer de CDD en contrat de professionnalisation 
            à CDI, c’est possible. Nous l\'invitons à nous appeler pour définir ensemble la marche à suivre. ', 3],
        'Puis-je rompre mon contrat de travail ? Dans quelle mesure ?' =>
            ['Votre contrat de travail est un CDD : il peut être rompu de manière unilatérale (par l’entreprise comme 
            par vous) et immédiate pendant la période d’essai qui est d’un mois pour un CDD d’un an. Les motifs et 
            conditions de rupture d’un CDD en dehors de cette période d’essai sont répertoriées ici.
            <br/><br/>La règle définie par Rocket School est la suivante :
            <br/><br/>1 - Si la rupture de contrat intervient à l\'initiative de l\'entreprise : nous vous donnons un 
            mois pour trouver une nouvelle entreprise en vous permettant de suivre les cours gratuitement. Etant donné 
            le nombre d\'offres sur ces métiers, la plupart des étudiants dans cette situation retrouvent un contrat 
            dans ce délai. Nous vous laissons chercher en autonomie pendant les deux premières semaines. Si vous êtes 
            sérieux.se et présent.e tous les jours dans nos locaux pour chercher alors nous activons nos réseaux pour 
            vous aider. Au delà de ce mois, nous vous laissons poursuivre les cours à Rocket School mais il vous 
            faudra financer votre formation si vous n’avez pas trouvé d’entreprise.
            <br/><br/>2 - Si la rupture de contrat est à l\'initiative de l\'étudiant : 
            <br/>- si c\'est un cas de force majeure (harcèlement, salaire non payé etc.) nous vous accompagnons pour 
            retrouver un contrat comme si la rupture était à l’initiative de l’entreprise.
            <br/>- si c\'est une "démission par choix" : vous ne serez plus autorisé.e à poursuivre votre parcours à 
            Rocket School. En effet, vous avez un engagement à tenir vis à vis de l’entreprise qui vous accueille, 
            qui vous a attendue pendant 3 mois et qui finance votre formation. Nous sommes donc particulièrement 
            vigilants au respect des engagements pris.', 3],
        'A quels espaces ai-je accès ?' =>
            ['Une salle de cours est mise à votre disposition pendant tout le bootcamp mais vous pouvez accéder à tous 
            les espaces communs pour travailler. En outre vous avez accès aux espaces de restauration (la “dînette” 
            et la “cantine”) où vous disposez notamment de distributeurs, de frigos et de micro-ondes.', 4],
        'Existe-t-il un parking pour me garer ?' =>
            ['Le Campus ne dispose pas d’un parking privé. Il existe des places payantes autour du Campus et au centre 
            commercial Confluence.
            <br/><br/>Les moyens de transport les plus pratiques pour accéder au campus sont les transports en commun 
            (ligne T1 - Arrêt “Hôtel de Région Montrochet) et les 2 roues (station Vélo\'v à proximité).', 4],
        'Quelles sont les règles de circulation sur le Campus ?' =>
            ['Le Campus est ouvert de 8h à 20h. A partir de 20h les portes ne s’ouvrent plus que pour sortir. ', 4],
    ];

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {

        $categories = [
            $this->getReference('category_1'),
            $this->getReference('category_2'),
            $this->getReference('category_3'),
            $this->getReference('category_4'),
            $this->getReference('category_5'),
        ];

        $index = 0;
        foreach (self::FAQ as $question => $answer) {
            $faq = new Faq();
            $faq->setQuestion($question)
                ->setAnswer($answer[0])
                ->setCreatedAt(date_create('now'))
                ->setCategory($categories[$answer[1]])
                ->setPosition($index);
            $index++;
            $manager->persist($faq);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupProd'];
    }
}
