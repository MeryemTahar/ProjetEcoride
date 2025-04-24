import { Component, OnInit } from '@angular/core';
import { CommonModule }      from '@angular/common';
import { FormsModule }       from '@angular/forms';
import { Router }            from '@angular/router';

interface Ride {
  from: string;
  to: string;
  date: string;
  departureTime: string;
  arrivalTime: string;
  driverName: string;
  driverPhoto: string;
  rating: number;
  seats: number;
  price: number;
  isElectric: boolean;
  duration: number; // en heures
}

@Component({
  selector: 'app-search-ride',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './search-ride.component.html',
  styleUrls: ['./search-ride.component.scss']
})
export class SearchRideComponent implements OnInit {
  // Champs de saisie
  from = '';
  to = '';
  date = '';
  departureTime = '';
  seats = 1;

  // Filtres
  filterEco = false;
  filterMaxPrice: number | null = null;
  filterMaxDuration: number | null = null;
  filterMinRating = 0;

  // Résultats bruts
  rides: Ride[] = [];

  cityOptions: string[] = [
    'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Bordeaux', 'Strasbourg',
    'Montpellier', 'Lille', 'Rennes', 'Reims', 'Le Havre', 'Saint-Etienne',
    'Toulon', 'Grenoble', 'Dijon', 'Angers', 'Nîmes', 'Villeurbanne', 'Nice', 'Brest'
  ];

  constructor(private router: Router) {}

  ngOnInit(): void {}
  

  onSearch(): void {
    // Remplace par ton appel API/service réél
    this.rides = [
      { from: this.from||'Paris', to: this.to||'Lille', date: this.date||new Date().toISOString().slice(0,10),
        departureTime: '08:00', arrivalTime: '12:00',
        driverName: 'Bob', driverPhoto: 'assets/images/bob.png',
        rating: 5, seats: 3, price: 39, isElectric: true, duration: 4 },
      { from: this.from||'Paris', to: this.to||'Lille', date: this.date||new Date().toISOString().slice(0,10),
        departureTime: '09:30', arrivalTime: '13:30',
        driverName: 'Alice', driverPhoto: 'assets/images/Alice_Durand.png',
        rating: 4.8, seats: 2, price: 51.5, isElectric: false, duration: 4 }
    ];
  }
  onReset(): void {
    // Remise à zéro des champs de recherche
    this.from = '';
    this.to = '';
    this.date = '';
    this.departureTime = '';
    this.seats = 1;

    // Remise à zéro des filtres
    this.filterEco = false;
    this.filterMaxPrice = null;
    this.filterMaxDuration = null;
    this.filterMinRating = 0;

    // Vidage des résultats
    this.rides = [];
  }

  /** Retourne la liste des trajets après application des filtres **/
  get filteredRides(): Ride[] {
    return this.rides
      // places dispo
      .filter((r: Ride) => r.seats > 0)
      // écolo ?
      .filter((r: Ride) => this.filterEco ? r.isElectric : true)
      // prix max
      .filter((r: Ride) => (this.filterMaxPrice != null) ? r.price <= this.filterMaxPrice : true)
      // durée max
      .filter((r: Ride) => (this.filterMaxDuration != null) ? r.duration <= this.filterMaxDuration : true)
      // note min
      .filter((r: Ride) => r.rating >= this.filterMinRating)
      // heure de départ
      .filter((r: Ride) => this.departureTime !== '' ? r.departureTime >= this.departureTime : true);
  }

  goToDetails(ride: Ride): void {
    this.router.navigate(['/ride-details'], {
      queryParams: {
        from: ride.from,
        to:   ride.to,
        date: ride.date,
        time: ride.departureTime
      }
    });
  }
}
