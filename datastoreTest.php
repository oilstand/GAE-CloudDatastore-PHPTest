<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Datastore\DatastoreClient;

function build_datastore_service($projectId)
{
    $datastore = new DatastoreClient(['projectId' => $projectId]);
    return $datastore;
}

$projectId = getenv('PROJECT_ID');

$datastore = build_datastore_service($projectId);

$postdat = false;
if(isset($_POST['kind']) && $_POST['kind'] != ""
    && isset($_POST['key_value']) && $_POST['key_value'] != "")
{
    $kind = $_POST['kind'];
    $postdat = true;
    if(isset($_POST['parent_key_value']) && $_POST['parent_key_value'] != "")
    {
        $entityKey = $datastore->key($kind, $_POST['parent_key_value'])->pathElement($kind, $_POST['key_value']);
    }
    else
    {
        $entityKey = $datastore->key($kind, $_POST['key_value']);
    }

    $entity = $datastore->entity($entityKey, [
                'data' => $_POST['data_value'],
                'description' => 'noindex data'
                ],
                ['excludeFromIndexes' => ['description']]
                );

    $datastore->upsert($entity);
}

$getkeyvalue = null;
if(isset($_GET['kind']) && $_GET['kind'] != ""
    && isset($_GET['key_value']) && $_GET['key_value'] != "")
{
    $kind = $_GET['kind'];
    $postdat = true;
    if(isset($_GET['parent_key_value']) && $_GET['parent_key_value'] != "")
    {
        $entityKey = $datastore->key($kind, $_GET['parent_key_value'])->pathElement($kind, $_GET['key_value']);
    }
    else
    {
        $entityKey = $datastore->key($kind, $_GET['key_value']);
    }

    $entity = $datastore->lookup($entityKey);

    $getkeyvalue = $entity->get();
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>datastore test</title>
    </head>
    <body>
        <?php if($postdat): ?>
        <pre>
<?php var_dump($_POST); ?>
        </pre>
        <hr>
        <?php endif; ?>
        <form method="POST" action="/">
            <input type="text" name="kind" value="Task">
            <input type="text" name="parent_key_value">
            <input type="text" name="key_value">
            <input type="text" name="data_value">
            <input type="submit" value="送信">
        </form>
        <hr>
        <form method="GET" action="/">
            <input type="text" name="kind" value="Task">
            <input type="text" name="parent_key_value">
            <input type="text" name="key_value">
            <input type="submit" value="送信">
        </form>
<?php if($getkeyvalue != null): ?>
        <pre>
<?php var_dump($getkeyvalue); ?>
        </pre>
<?php endif; ?>