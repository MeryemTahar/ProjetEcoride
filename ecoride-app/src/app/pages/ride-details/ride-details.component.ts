import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { RideService } from '../../services/ride.service';
import { AuthService } from '../../services/auth/auth.service'; 

@Component({
  selector: 'app-ride-details',
  standalone: true,
  imports : [CommonModule],
  templateUrl: './ride-details.component.html',
  styleUrls: ['./ride-details.component.scss']
})
export class RideDetailsComponent implements OnInit {

  public ride: {
    from: string;
    to: string;
    date: string;
    departureTime: string;
    credits: number;
  } = {
    from: '',
    to: '',
    date: '',
    departureTime: '',
    credits: 0
  };
  
  public userCredit = 70;
  public confirming = false;
  // Propriétés pour les détails du trajet
  tripDate: string = '2025-04-21';
  tripTime: string = '09:30';
  seatsRemaining: number = 3;
  price: number = 25;
  isEco: boolean = true;

  // Informations sur le conducteur
  driverName: string = 'Bob Martin';
  driverRating: number = 4.8;
  driverRatingCount: number = 12;
  driverPhotoUrl: string = 'assets/images/bob.png';

  // Préférences du conducteur
  driverPreferences: string[] = ['Non-fumeur', 'Musique relax'];

  // Informations sur le véhicule
  vehicleBrand: string = 'Tesla';
  vehicleModel: string = 'Model 3';
  vehicleEnergy: string = 'Électrique';
  vehicleColor: string = 'Noir';

  

  // Avis des passagers
  passengerReviews: Array<{ reviewer: string; rating: number; comment: string }> = [
    {
      reviewer: 'Alice Durand',
      rating: 5,
      comment: 'Super trajet, chauffeur très sympa !'
    },
    {
      reviewer: 'Franck Friard',
      rating: 4,
      comment: 'Voiture confortable, chauffeur sympathique, je recommande.'
    }

  ];

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private rideService: RideService,
    private authService: AuthService

  ) {}

  ngOnInit(): void {

    const qp = this.route.snapshot.queryParamMap;
    this.ride = {
      from: qp.get('from')  || 'Paris',
      to:   qp.get('to')    || 'Lyon',
      date: qp.get('date')  || '2025-04-17',
      departureTime: qp.get('time') || '09:30',
      credits: Number(qp.get('credits')) || 25
    };
    // Récupérer les query params transmis si besoin (par exemple : date, heure, etc.)
    this.route.queryParams.subscribe(params => {
      if (params['date']) {
        this.tripDate = params['date'];
      }
      if (params['time']) {
        this.tripTime = params['time'];
      }
      // Ajout d'autres paramètres selon tes besoins
    });
  }

  onParticiperClick(): void {
    this.confirming = true;
  }

  /** valider la confirmation du paiement */
  confirmParticipation(): void {
    // ici tu appelleras ton RideService pour envoyer la participation au back‑end
    console.log('Participation confirmée pour', this.ride);
    // puis on ferme le modal
    this.confirming = false;
    // et on redirige vers la page de paiement éventuelle
    this.router.navigate(['/payment'], { queryParams: { /* … */ } });
  }

  /** annuler la popup */
  cancelConfirm(): void {
    this.confirming = false;
  }
}