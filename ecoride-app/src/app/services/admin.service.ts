// src/app/services/admin.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';

export interface RideCount { date: string; count: number; }
export interface CreditCount { date: string; credits: number; }
export interface Account   { id: number; role: string; pseudo: string; isSuspended: boolean; }

@Injectable({ providedIn: 'root' })
export class AdminService {

  private baseUrl = 'https://votre-backend/api/admin';

  constructor(private http: HttpClient) {}

  fetchTotalCredits(): Observable<number> {
    // en prod tu feras :
    // return this.http.get<{ total: number }>(`${this.baseUrl}/total-credits`)
    //           .pipe(map(resp => resp.total));
    // pour l'instant mock :
    return of(1234);
  }

  fetchRidesPerDay(): Observable<RideCount[]> {
    // return this.http.get<RideCount[]>(`${this.baseUrl}/rides-per-day`);
    return of([
      { date: '2025-04-01', count: 5 },
      { date: '2025-04-02', count: 8 },
      { date: '2025-04-03', count: 3 },
    ]);
  }

  fetchCreditsPerDay(): Observable<CreditCount[]> {
    // return this.http.get<CreditCount[]>(`${this.baseUrl}/credits-per-day`);
    return of([
      { date: '2025-04-01', credits: 25 },
      { date: '2025-04-02', credits: 40 },
      { date: '2025-04-03', credits: 15 },
    ]);
  }

   /** ← ici on passe du mock à l’appel HTTP réel */
   fetchAccounts(): Observable<Account[]> {
    // Mock temporaire :
    return of([
   { id: 1, role: 'Utilisateur', pseudo: 'Alice',  isSuspended: false },
   { id: 2, role: 'Employé',     pseudo: 'Franck', isSuspended: false },
     ]);

    // Appel réel à ton back-end :
    // return this.http.get<Account[]>(`${this.baseUrl}/accounts`);
  }


 /** Suspend un compte */
 suspend(id: number): Observable<void> {
    console.debug(`Mock suspend(${id})`);
    return of(void 0);
  //en prod 
 // return this.http.post<void>(`${this.baseUrl}/suspend`, { id });
}

/** Réactive un compte suspendu */
unsuspendAccount(id: number): Observable<void> {
  console.debug(`Mock unsuspend(${id})`);
    return of(void 0);

  // en prod :
  //return this.http.post<void>(`${this.baseUrl}/unsuspend/${id}`, {});
  }

}
