<?php include 'header.php'; ?>

        <div class="jumbotron">
            <div class="container">
                <h1>Hello, welcome to <?= \Utils\Config::get("project_name") ?></h1>
                <p>An Interactive tool to help support Agile teams with story point estimation </p>
            </div>
        </div>

        <div class="container">
            <p><?= \Utils\Config::get("project_name") ?> is a tool designed to support agile teams in story
            point estimation. The vision for the tool is to support big and small local and remote teams
            and to integrate with the best agile tools in the business</p>
            <p>Currentley running version <?= \Utils\Config::get("version") ?> </p>
            <p>In this version you are able to</p>
            <p><ul>
                <li>Add a story to be estimated</li>
                <li>Add multiple users votes on the same screen</li>
                <li>Have multiple estimation rounds</li>
                <li>Cast a final estimate</li>
            </ul></p>
            <p>This is an open source project so we are always looking for contributors. You can fork the repository
            at <?= \Utils\Config::get("repository") ?>. </p>
            <p>Its just developer we are always after support from testers and UX/UI engineers.</p>
        </div>

<?php include 'footer.php';
