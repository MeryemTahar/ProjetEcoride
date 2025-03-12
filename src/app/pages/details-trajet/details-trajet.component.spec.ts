import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailsTrajetComponent } from './details-trajet.component';

describe('DetailsTrajetComponent', () => {
  let component: DetailsTrajetComponent;
  let fixture: ComponentFixture<DetailsTrajetComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DetailsTrajetComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DetailsTrajetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
