<?php
    // Helper function for clean text formatting in seeded content"
    if (!function_exists('unwrap')) {
        function unwrap(string $text): string {
            $text = preg_replace('/\s*\n\s*/', ' ', trim($text));
            $text = preg_replace('/\s*#2n\s*/', "\n\n", $text);
            $text = preg_replace('/\s*#n\s*/', "\n", $text);
            $text = preg_replace('/ {2,}/', ' ', $text);
            return trim($text);
        }
    }

    // Dummy data for testing/presentation
    return [
        'initTestUsers' => [
            [
                'name'       => 'admin',
                'password'   => '$2b$12$iu9YSTsezVGeoeAFjnIHbuYkqU/nBZsTL1NQmpsmcZguoGvqfBRiS',
                'permission' => 15,
            ],
            [
                'name'       => 'reader',
                'password'   => '$2b$12$iu9YSTsezVGeoeAFjnIHbuYkqU/nBZsTL1NQmpsmcZguoGvqfBRiS',
                'permission' => 1,
            ],

        ],
        'initTestArticles' => [
            [
                'title'   => 'Mein erstes Fullstack-Projekt',
                'content' => unwrap(<<<EOT
                                Dieses Projekt ist eine einfache Blog-Seite mit Login. Es ist mein erstes eigenes Fullstack-Projekt
                                und zugleich mein erster praktischer Einstieg in die Backend-Entwicklung. #n
                                Mein Hauptziel war es, von Anfang an auf eine professionelle Softwarearchitektur und saubere Codequalität
                                zu achten. #2n

                                Als kreativer Mensch gestalte ich Dinge gerne einzigartig und möglichst perfekt. Ich bin sehr detailorientiert veranlagt
                                und stelle hohe Ansprüche an mich selbst sowie an eine starke visuelle Ästhetik. Ich mag echte Innovation 
                                gegenüber der Tendenz, Altbekanntes einfach immer wieder nur neu zu dekorieren. #n
                                Da dies jedoch sehr zeitaufwendig werden kann und ich mich bei kreativen Aufgaben neben dem Programmieren gerne 
                                verliere, habe ich die UI/UX in diesem Projekt bewusst dezent und funktional gehalten. #2n

                                Da es sich um mein erstes Fullstack-Projekt handelt, sind mit Sicherheit noch Anfängerfehler vorhanden. Das
                                Projekt ist für mich ein Lernprozess. Da ich mich ständig verbessern möchte, freue ich mich immer über Feedback von
                                erfahrenen Entwicklern. #2n

                                Eine Blog-Seite ist grundsätzlich nichts Besonderes und auch diese hier ist nicht perfekt. Für ein erstes Fullstack-Projekt,
                                bei dem man vieles zum ersten Mal richtig umzusetzen und zu verstehen versucht, bin ich jedoch sehr zufrieden mit der Qualität
                                des Quellcodes, insbesondere mit der Projektstruktur. #2n

                                Alle Projekte werden auf meinem GitHub-Account als Repositories verfügbar sein. Schau gerne vorbei, wenn dich die
                                Implementierung oder die Softwarearchitektur interessiert. Es werden in Zukunft noch deutlich interessantere und nützliche Projekte entstehen 
								und u.U. so manche, noch unbekannte, Nischen öffnen.
                            EOT)
            ],
            [
                'title'   => 'Was diese Blog-Seite zu bieten hat',
                'content' => unwrap(<<<EOT
                                Die Seite nutzt ein klassisches Login- und Logout-System. Für die Nutzung habe ich zwei Benutzer angelegt: Admin und Leser.
                                Der Leser dient primär als Testnutzer, um die verschiedenen Berechtigungen überprüfen zu können. #n
                                Da mir das Prinzip der Rollen- und Rechtevergabe bereits bekannt ist, habe ich bewusst darauf verzichtet,
                                spezialisierte Nutzerrollen zu definieren. Denkbar wäre beispielsweise eine Rolle "Autor", die ähnliche Rechte wie
                                ein Admin besitzt, jedoch ausschließlich für die eigenen Beiträge. #2n

                                Dir ist vermutlich bereits aufgefallen, dass nach dem ersten Aufruf der Seite schon Artikel
                                wie dieser vorhanden sind. Ich habe in der Konfiguration festgelegt, dass die Datenbank beim ersten Start automatisch
                                Seed-Daten für neue Besucher anlegt. #2n

                                Außerdem habe ich bei diesem Projekt zum ersten Mal eine Website internationalisiert und mich bemüht, auf verschiedenen 
                                Ebenen barrierefrei zu arbeiten. Die Sprachen werden in separaten Dateien als Wörterbücher gepflegt und über Schlüsselwörter 
                                in das Projekt eingebunden. Die Sprachen "Deutsch" und "Englisch" können direkt über einen Sprachschalter ausgewählt werden. #2n

                                Auf der Startseite findest du zwei zentrale UI-Bereiche: eine Übersicht aller erstellten Artikel mit den Aktionen
                                "Bearbeiten" und "Löschen" sowie einen Editor-Bereich zum Erstellen und Bearbeiten von Beiträgen. Um einen neuen
                                Artikel anzulegen, klicke auf das '+'-Symbol. Daraufhin wird ein leerer Editor eingeblendet. #n
                                Möchtest du einen bestehenden Artikel bearbeiten, klickst du auf das Stift-Symbol. Der Editor wird dann mit dem
                                entsprechenden Inhalt vorgefüllt angezeigt. Über das Mülleimer-Symbol kannst du den zugehörigen Artikel löschen.
                                Vor dem endgültigen Löschen erscheint zusätzlich eine Sicherheitsabfrage. #2n

                                Möchtest du einen Artikel lesen, genügt ein Klick auf den entsprechenden Eintrag. Der Inhalt wird anschließend in
                                einem Modalfenster dargestellt, das wie ein beschriebenes Blatt Papier gestaltet ist. #2n

                                Ich habe versucht, die Bedienung möglichst benutzerfreundlich zu gestalten. Selbst wenn irgendwann Tausende von
                                Einträgen vorhanden sein sollten, soll niemand durch ein Labyrinth navigieren müssen. Passend zum Insel-Thema sind
                                die Buttons als Lichtschalter gestaltet. Wenn du eine Aktion auswählst, bleibt der entsprechende Schalter beleuchtet,
                                solange die Aktion aktiv ist. #n
                                Zusätzlich habe ich ein kleines Leitlicht eingebaut, das während der Nutzung Orientierung nach einer Interaktion bietet. #2n

                                Die Icons und Bilder habe ich mit ChatGPT erstellen lassen. Die generierten Grafiken dienten anschließend
                                als Grundlage für mein einheitliches Hintergrunddesign. Für die Nachbearbeitung der Bilder kam 'GIMP' zum Einsatz.
                            EOT)
            ],
        ],
    ];