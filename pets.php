<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include 'db.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getPets':
        getPets($connection);
        break;
    case 'addPet':
        addPet($connection);
        break;
    case 'editPet':
        editPet($connection);
        break;
    case 'getSpecies':
        getSpecies($connection);
        break;
    case 'addSpecies':
        addSpecies($connection);
        break;
    case 'getBreeds':
        getBreeds($connection);
        break;
    case 'addBreed':
        addBreed($connection);
        break;
    case 'getOwners':
        getOwners($connection);
        break;
    case 'addOwner':
        addOwner($connection);
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$connection->close();

function getPets($connection) {
    $query = "SELECT Pets.PetID, Pets.Name AS PetName, Species.SpeciesName, Breeds.BreedName, Pets.DateOfBirth, Owners.Name AS OwnerName
              FROM Pets
              JOIN Species ON Pets.SpeciesID = Species.SpeciesID
              JOIN Breeds ON Pets.BreedID = Breeds.BreedID
              JOIN Owners ON Pets.OwnerID = Owners.OwnerID";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $row;
        }
        echo json_encode($pets);
    } else {
        echo json_encode([]);
    }
}

function addPet($connection) {
    $petName = $_POST['name'];
    $speciesID = $_POST['speciesID'];
    $breedID = $_POST['breedID'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $ownerID = $_POST['ownerID'];

    $stmt = $connection->prepare("INSERT INTO Pets (Name, SpeciesID, BreedID, DateOfBirth, OwnerID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $petName, $speciesID, $breedID, $dateOfBirth, $ownerID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to add pet']);
    }

    $stmt->close();
}

function editPet($connection) {
    $petID = $_POST['petID'];
    $petName = $_POST['name'];
    $speciesID = $_POST['speciesID'];
    $breedID = $_POST['breedID'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $ownerID = $_POST['ownerID'];

    $stmt = $connection->prepare("UPDATE Pets SET Name=?, SpeciesID=?, BreedID=?, DateOfBirth=?, OwnerID=? WHERE PetID=?");
    $stmt->bind_param("sssssi", $petName, $speciesID, $breedID, $dateOfBirth, $ownerID, $petID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to update pet']);
    }

    $stmt->close();
}

function getSpecies($connection) {
    $query = "SELECT * FROM Species";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $species = [];
        while ($row = $result->fetch_assoc()) {
            $species[] = $row;
        }
        echo json_encode($species);
    } else {
        echo json_encode([]);
    }
}

function addSpecies($connection) {
    $speciesName = $_POST['speciesName'];

    $stmt = $connection->prepare("INSERT INTO Species (SpeciesName) VALUES (?)");
    $stmt->bind_param("s", $speciesName);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to add species']);
    }

    $stmt->close();
}

function getBreeds($connection) {
    $query = "SELECT * FROM Breeds";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $breeds = [];
        while ($row = $result->fetch_assoc()) {
            $breeds[] = $row;
        }
        echo json_encode($breeds);
    } else {
        echo json_encode([]);
    }
}

function addBreed($connection) {
    $breedName = $_POST['breedName'];

    $stmt = $connection->prepare("INSERT INTO Breeds (BreedName) VALUES (?)");
    $stmt->bind_param("s", $breedName);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to add breed']);
    }

    $stmt->close();
}

function getOwners($connection) {
    $query = "SELECT OwnerID, Name AS OwnerName, ContactDetails AS OwnerContactDetails, Address AS OwnerAddress FROM Owners";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $owners = [];
        while ($row = $result->fetch_assoc()) {
            $owners[] = $row;
        }
        echo json_encode($owners);
    } else {
        echo json_encode([]);
    }
}


function addOwner($connection) {
    $ownerName = $_POST['ownerName'];
    $ownerContactDetails = $_POST['ownerContactDetails'];
    $ownerAddress = $_POST['ownerAddress'];

    $stmt = $connection->prepare("INSERT INTO Owners (Name, ContactDetails, Address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ownerName, $ownerContactDetails, $ownerAddress);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to add owner']);
    }

    $stmt->close();
}
?>
