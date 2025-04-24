import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';

export interface Ride {
  id: string;
  from: string;
  to: string;
  date: string;
  departureTime: string;
  credits: number;
  seats: number;
}

@Injectable({ providedIn: 'root' })
export class RideService {
  /** Récupère les détails d’un trajet (appel REST réel plus tard) */
  getRideDetails(params: {
    from: string;
    to?: string;
    date?: string;
    time?: string;
  }): Observable<Ride> {
    // mock pour l’instant
    return of({
      id: 'abc123',
      from: params.from,
      to: params.to ?? 'Lyon',
      date: params.date ?? '2025-04-21',
      departureTime: params.time ?? '09:30',
      credits: 5,
      seats: 3,
    });
  }

  /** Participe au covoiturage (débit de crédit + décrément place) */
  participate(rideId: string): Observable<void> {
    // appel HTTP POST etc.
    console.log(`participate(${rideId})`);
    return of(void 0);
  }
}

