import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-publish-ride',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './publish-ride.component.html',
  styleUrls: ['./publish-ride.component.scss']
})
export class PublishRideComponent {
  departureCity = '';
  arrivalCity = '';
  departureDate: string = '';
  departureTime: string = '';
  seatsAvailable: number = 1;
  preferences = '';
  selectedVehicle: string = ''; // Nouvelle prop pour le véhicule sélectionné

  vehicleOptions = [
    { id: '1', brand: 'Renault', model: 'Clio 5', licensePlate: 'ZX-009-RR', type: 'thermique' },
    { id: '2', brand: 'Peugeot', model: '208', licensePlate: 'AB-123-CD', type: 'thermique' },
    { id: '3', brand: 'Tesla', model: '3', licensePlate: 'XY-456-ZZ', type: 'électrique' },
    // Ajoute d'autres véhicules selon tes besoins...
  ];

  // Déclaration des options pour le <select> des villes
  cityOptions: string[] = [
    'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Bordeaux', 'Strasbourg',
    'Montpellier', 'Lille', 'Rennes', 'Reims', 'Le Havre', 'Saint-Etienne',
    'Toulon', 'Grenoble', 'Dijon', 'Angers', 'Nîmes', 'Villeurbanne', 'Nice', 'Brest'
  ];

  // Juste après la liste des villes, tu peux déclarer les coordonnées pour chaque ville :
  cityCoords: Record<string, { lat: number; lng: number }> = {
    // 1. Paris
    Paris:        { lat: 48.8566,  lng: 2.3522 },
    // 2. Marseille
    Marseille:    { lat: 43.2965,  lng: 5.3698 },
    // 3. Lyon
    Lyon:         { lat: 45.7640,  lng: 4.8357 },
    // 4. Toulouse
    Toulouse:     { lat: 43.6047,  lng: 1.4442 },
    // 5. Bordeaux
    Bordeaux:     { lat: 44.8378,  lng: -0.5792 },
    // 6. Strasbourg
    Strasbourg:   { lat: 48.5734,  lng: 7.7521 },
    // 7. Montpellier
    Montpellier:  { lat: 43.6108,  lng: 3.8767 },
    // 8. Lille
    Lille:        { lat: 50.6292,  lng: 3.0573 },
    // 9. Rennes
    Rennes:       { lat: 48.1173,  lng: -1.6778 },
    // 10. Reims
    Reims:        { lat: 49.2583,  lng: 4.0317 },
    // 11. Le Havre
    'Le Havre':   { lat: 49.4944,  lng: 0.1079 },
    // 12. Saint-Etienne
    'Saint-Etienne': { lat: 45.4397, lng: 4.3872 },
    // 13. Toulon
    Toulon:       { lat: 43.1258,  lng: 5.9302 },
    // 14. Grenoble
    Grenoble:     { lat: 45.1885,  lng: 5.7245 },
    // 15. Dijon
    Dijon:        { lat: 47.3220,  lng: 5.0415 },
    // 16. Angers
    Angers:       { lat: 47.4784,  lng: -0.5632 },
    // 17. Nîmes
    'Nîmes':      { lat: 43.8367,  lng: 4.3601 },
    // 18. Villeurbanne
    Villeurbanne: { lat: 45.7719,  lng: 4.8902 },
    // 19. Nice
    Nice:         { lat: 43.7102,  lng: 7.2620 },
    // 20. Brest
    Brest:        { lat: 48.3904,  lng: -4.4861 },
  };

  distanceKm: number = 0;
  co2Emission: number = 0;
  savingEuro: number = 0;

  onChangeCities() {
    if (this.departureCity && this.arrivalCity && this.departureCity !== this.arrivalCity) {
      const { lat: lat1, lng: lng1 } = this.cityCoords[this.departureCity];
      const { lat: lat2, lng: lng2 } = this.cityCoords[this.arrivalCity];
      this.distanceKm = haversineDistance(lat1, lng1, lat2, lng2);

      // Calcul des émissions de CO₂ (ex: 0.12 kg CO₂/km)
      this.co2Emission = +(this.distanceKm * 0.12).toFixed(2);

      // Calcul des économies en euros (ex: coût perso 0,20€/km, tarif covoiturage = 50%)
      const costPersonalCar = this.distanceKm * 0.2;
      const userPaidCovoit = costPersonalCar * 0.5;
      this.savingEuro = +(costPersonalCar - userPaidCovoit).toFixed(2);
    } else {
      this.distanceKm = 0;
      this.co2Emission = 0;
      this.savingEuro = 0;
    }
  }

  onSubmit() {
    console.log('Formulaire soumis avec', {
      departureCity: this.departureCity,
      arrivalCity: this.arrivalCity,
      departureDate: this.departureDate,
      departureTime: this.departureTime,
      seatsAvailable: this.seatsAvailable,
      preferences: this.preferences
    });
    // Ici, ajoute la logique pour envoyer les données au backend
  }

  onResetForm() {
    this.departureCity = '';
    this.arrivalCity = '';
    this.departureDate = '';
    this.departureTime = '';
    this.seatsAvailable = 1;
    this.preferences = '';
    this.distanceKm = 0;
    this.co2Emission = 0;
    this.savingEuro = 0;
    console.log('Formulaire réinitialisé.');
  }
}

// Fonction utilitaire (externe ou à placer dans ce fichier)
function haversineDistance(lat1: number, lon1: number, lat2: number, lon2: number): number {
  const R = 6371; // Rayon de la Terre en km
  const dLat = (lat2 - lat1) * (Math.PI / 180);
  const dLon = (lon2 - lon1) * (Math.PI / 180);
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}


