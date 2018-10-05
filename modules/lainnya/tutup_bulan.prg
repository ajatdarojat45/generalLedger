SET EXACT OFF 

nOpt=thisform.optiongroup1.Value 
nPerSis=ALLTRIM(STR(thisform.spinner1.Value))+RIGHT('00'+ALLTRIM(STR(thisform.combo1.Value)),2)
nPerAktif=ALLTRIM(STR(thisform.spinner2.Value))+RIGHT('00'+ALLTRIM(STR(thisform.combo2.Value)),2)
nPer=ALLTRIM(STR(thisform.spinner3.Value))+RIGHT('00'+ALLTRIM(STR(thisform.combo3.Value)),2)

*!*	MESSAGEBOX(nPerSis)
*!*	MESSAGEBOX(nPerAktif)
*!*	MESSAGEBOX(nPer)
*!*	RETURN

IF nPerSis < nPer
	MESSAGEBOX('Periode Sistem Tidak Boleh Kurang Dari Periode Proses',0+64,'Perhatian')
	RETURN 
ENDIF 

IF nOpt=2
	SELECT 0 
	IF net_use2(zDrvMas+'\casecoa',.f.,'casecoa')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglm',.f.,'caseglm')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglmp',.f.,'caseglmp')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvTrs+'\caseglt',.f.,'caseglt')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_fin',.f.,'casefin')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_tam',.f.,'casetam')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\casepjk',.f.,'casepjk')
	ELSE
		RETURN
	ENDIF 
ELSE
	SELECT 0 
	IF net_use2(zDrvMas+'\casecoa',.t.,'casecoa')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglm',.t.,'caseglm')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglmp',.t.,'caseglmp')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvTrs+'\caseglt',.t.,'caseglt')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_fin',.t.,'casefin')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_tam',.t.,'casetam')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\casepjk',.t.,'casepjk')
	ELSE
		RETURN
	ENDIF 
	
****
	thisform.text1.Visible= .T. 
	thisform.text1.Value='Proses Pack dan Reindex Data'

	SELECT casecoa
	PACK 
	REINDEX 
	USE 
	
	SELECT caseglm
	PACK 
	REINDEX 
	USE 
	SELECT caseglmp
	USE 
	SELECT caseglt
	PACK
	REINDEX 
	USE 
	SELECT casefin
	PACK
	REINDEX 
	USE 
	SELECT casetam
	PACK
	REINDEX 
	USE 
	SELECT casepjk
	USE 
****
	SELECT 0 
	IF net_use2(zDrvMas+'\casecoa',.f.,'casecoa')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglm',.f.,'caseglm')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvMas+'\caseglmp',.f.,'caseglmp')
	ELSE
		RETURN
	ENDIF 

	SELECT 0 
	IF net_use2(zDrvTrs+'\caseglt',.f.,'caseglt')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_fin',.f.,'casefin')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\case_tam',.f.,'casetam')
	ELSE
		RETURN
	ENDIF 
	SELECT 0 
	IF net_use2(zDrvTrs+'\casepjk',.f.,'casepjk')
	ELSE
		RETURN
	ENDIF 
ENDIF 

thisform.text1.Visible= .T. 
thisform.text1.Value='Proses Saldo Awal'

nTahun=LEFT(nPer,4)
nBulan=RIGHT(nPer,2)
xBulan=RIGHT(nPerSis,2)

IF LEFT(nPerSis,4)<>nTahun
	xBulan='12'
ENDIF 

SELECT casecoa
SET ORDER TO 1
GO TOP
SCAN  
	cAcc=acc
	cTh=th_sld
	cBl=bl_sld
	cJml=jml_sld
	
	SELECT caseglm
	SET ORDER TO caseglm2
	SEEK cAcc+nTahun
	IF !FOUND()
		INSERT INTO caseglm(acc,per) VALUES (cAcc,nTahun)
	ENDIF 
	
	IF !EMPTY(cTh) AND !EMPTY(cBl) AND !EMPTY(cJml)
		cQry="UPDATE caseglm SET saw"+cBl+"=cJml, sawp"+cBl+"=cJml where acc==cAcc AND per==cTh"
		&cQry	
	ENDIF 

	SELECT casecoa
ENDSCAN 

thisform.text1.Value='Proses Data Transaksi'


SELECT caseglm
GO TOP 
mKas1='kasd'+RIGHT(nPer,2)
mKas2='kasc'+RIGHT(nPer,2)
mBnk1='bnkd'+RIGHT(nPer,2)
mBnk2='bnkc'+RIGHT(nPer,2)
mMem1='memd'+RIGHT(nPer,2)
mMem2='memc'+RIGHT(nPer,2)
mPjk1="nil_pjk1"+RIGHT(nPer,2)
mPjk2="nil_pjk2"+RIGHT(nPer,2)
		
REPLACE ALL &mKas1 WITH 0 FOR per=nTahun
REPLACE ALL &mKas2 WITH 0 FOR per=nTahun
REPLACE ALL &mBnk1 WITH 0 FOR per=nTahun
REPLACE ALL &mBnk2 WITH 0 FOR per=nTahun 
REPLACE ALL &mMem1 WITH 0 FOR per=nTahun
REPLACE ALL &mMem2 WITH 0 FOR per=nTahun
REPLACE ALL &mPjk1 WITH 0 FOR per=nTahun
REPLACE ALL &mPjk2 WITH 0 FOR per=nTahun

SELECT caseglm
SET ORDER TO caseglm1
SEEK nTahun
DO WHILE !EOF() AND per=nTahun	
	cAcc=acc

	cSaw=saw&nBulan
	cSawP=sawP&nBulan

	cKasD=kasd&nBulan
	cKasC=kasc&nBulan
	cBnkD=bnkd&nBulan
	cBnkC=bnkc&nBulan
	cMemD=memd&nBulan
	cMemC=memc&nBulan

	cSak=sak&nBulan
	cSakP=sakp&nBulan

	xSaw=cSaw
	xSak=cSak

	xSawP=cSawP
	xSakP=cSakP
	
	SELECT caseglt
	SET ORDER TO caseglt3
	SEEK cAcc
	STORE 0 TO xKasD,xKasC,xBnkD,xBnkC,xMemD,xMemC,xPjkD,xPjkK
	DO WHILE !EOF() AND acc=cAcc	
		cType=UPPER(typ)
		DO CASE
			CASE cType='K'  && Kas
				xKasD=xKasD+deb
				xKasC=xKasC+krd
				
			CASE cType='B'  && Bank
				xBnkD=xBnkD+deb
				xBnkC=xBnkC+krd

			OTHERWISE  && Memorial
				xMemD=xMemD+deb
				xMemC=xMemC+krd			
		ENDCASE 
		
		SELECT caseglt
		SKIP 
	ENDDO 
	
	SELECT casepjk
	SET ORDER TO casepjk3
	SEEK cAcc
	
	xPjkD=casepjk.deb	
	xPjkK=casepjk.krd

	SELECT casecoa
	SET ORDER TO casecoa1
	SEEK cAcc
	IF FOUND()
		cDK=tnd
	ELSE
		cDK=''
	ENDIF 
	
	SELECT caseglm
	
	cBulan=VAL(nBulan)
	tBulan=VAL(nBulan)
	DO WHILE cBulan <= VAL(xBulan) && update saldo awal dan saldo akhir bulan berikutnya		
		SELECT caseglm		
		IF tBulan=cBulan
			REPLACE KasD&nBulan	WITH xKasD
			REPLACE KasC&nBulan	WITH xKasC
			REPLACE BnkD&nBulan	WITH xBnkD
			REPLACE BnkC&nBulan	WITH xBnkC
			REPLACE MemD&nBulan	WITH xMemD
			REPLACE MemC&nBulan	WITH xMemC


			REPLACE nil_pjk1&nBulan WITH xPjkD	&& Koreksi Negatif
			REPLACE nil_pjk2&nBulan WITH xPjkK	&& Koreksi Positif

			IF cDK='D'
				REPLACE Sak&nBulan	WITH xSaw+xKasD-xKasC+xBnkD-xBnkC+xMemD-xMemC
				xSak=xSaw+xKasD-xKasC+xBnkD-xBnkC+xMemD-xMemC

				REPLACE SakP&nBulan	WITH xSawP+xPjkD-xPjkK
				xSakP=xSawP+xPjkD-xPjkK
			ELSE 
				REPLACE Sak&nBulan	WITH xSaw-xKasD+xKasC-xBnkD+xBnkC-xMemD+xMemC
				xSak=xSaw-xKasD+xKasC-xBnkD+xBnkC-xMemD+xMemC

				REPLACE SakP&nBulan	WITH xSawP-xPjkD+xPjkK
				xSakP=xSawP-xPjkD+xPjkK
			ENDIF 	

		ELSE
			vBulan=RIGHT('00'+ALLTRIM(STR(cBulan)),2)
			REPLACE Saw&vBulan	WITH xSak
			REPLACE Sawp&vBulan	WITH xSakP
			
			IF cDK='D'
				xSak=xSak+KasD&vBulan-KasC&vBulan+BnkD&vBulan-BnkC&vBulan+MemD&vBulan-MemC&vBulan
				xSakP=xSakP + nil_pjk1&vBulan - nil_pjk2&vBulan
			ELSE
				xSak=xSak-KasD&vBulan+KasC&vBulan-BnkD&vBulan+BnkC&vBulan-MemD&vBulan+MemC&vBulan
				xSakP=xSakP - nil_pjk1&vBulan + nil_pjk2&vBulan
			ENDIF 	
			
			REPLACE Sak&vBulan	WITH xSak	
			REPLACE SakP&vBulan	WITH xSakP
			
		ENDIF 		
		cBulan=cBulan+1
	ENDDO 
	
	SELECT caseglm
	SKIP 
ENDDO 

thisform.text1.Value='Proses Saldo Akhir'

SELECT caseglm
SET ORDER TO caseglm1
SEEK nTahun
DO WHILE !EOF() AND per=nTahun
	SELECT caseglm
	cAcc=acc
	IF RIGHT(cAcc,2)='00'
		cBulan=VAL(nBulan)
		tBulan=VAL(nBulan)			
		DO WHILE cBulan <= VAL(xBulan) && update saldo awal dan saldo akhir bulan berikutnya						
			xSaw='saw'+RIGHT('00'+ALLTRIM(STR(cBulan)),2)
			xSak='sak'+RIGHT('00'+ALLTRIM(STR(cBulan)),2)
			vBulan=RIGHT('00'+ALLTRIM(STR(cBulan)),2)
			REPLACE Saw&vBulan WITH 0
			REPLACE Sak&vBulan WITH 0
			REPLACE Ang&vBulan WITH 0
			REPLACE Eli&vBulan WITH 0
			
			REPLACE SawP&vBulan WITH 0
			REPLACE SakP&vBulan WITH 0
			
			cBulan=cBulan+1
		ENDDO 
	ENDIF 
	SELECT caseglm
	SKIP 
ENDDO 

DIMENSION cSaw(12)
DIMENSION cSak(12)
DIMENSION cAng(12)
DIMENSION cEli(12)

DIMENSION cSawP(12)
DIMENSION cSakP(12)

SELECT acc,tnd,level FROM casecoa ORDER BY acc INTO CURSOR csrCOA

SELECT csrCOA
GO TOP 
DO WHILE !EOF()
	SELECT csrCOA
	IF Level=5
		cAcc=acc   && 1-01-01-01-01
		cAcc1=LEFT(cAcc,1)+'-00-00-00-00'
		cAcc2=LEFT(cAcc,4)+'-00-00-00'
		cAcc3=LEFT(cAcc,7)+'-00-00'
		cAcc4=LEFT(cAcc,10)+'-00'
		cAcc5=cAcc		
		cDK=tnd
		
		SELECT caseglm
		SET ORDER TO caseglm1
		SEEK nTahun+cAcc
		IF FOUND()
			FOR m=1 TO 12
				cSaw(m)=0
				cSak(m)=0				
				cAng(m)=0
				cEli(m)=0
				cSawP(m)=0
				cSakP(m)=0				
			NEXT  
			cBulan=VAL(nBulan)
			tBulan=VAL(nBulan)			
			DO WHILE cBulan <= VAL(xBulan) 
				vBulan=RIGHT('00'+ALLTRIM(STR(cBulan)),2)				
				cSaw(cBulan)=Saw&vBulan
				cSak(cBulan)=Sak&vBulan
				cAng(cBulan)=Ang&vBulan	
				cEli(cBulan)=Eli&vBulan
					
				cSawP(cBulan)=SawP&vBulan
				cSakP(cBulan)=SakP&vBulan
				
				cBulan=cBulan+1
			ENDDO 
			SELECT caseglm
			SET ORDER TO caseglm1
			SEEK nTahun+cAcc4
			IF FOUND()
				SELECT casecoa
				SET ORDER TO casecoa1
				SEEK cAcc4
				xDK=IIF(FOUND(),tnd,'D')
				
				SELECT caseglm
				FOR m=1 TO 12
					vBulan=RIGHT('00'+ALLTRIM(STR(m)),2)
					IF xDK='D'
						IF cDK='D'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)
							
							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)
							
							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ELSE
						IF cDK='K'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)
							
							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ENDIF 					
										
					REPLACE Ang&vBulan	WITH Ang&vBulan + cAng(m)					
					REPLACE Eli&vBulan	WITH Eli&vBulan + cEli(m)					
				NEXT 				
			ENDIF 
			SELECT caseglm
			SET ORDER TO caseglm1
			SEEK nTahun+cAcc3
			IF FOUND()
				SELECT casecoa
				SET ORDER TO casecoa1
				SEEK cAcc3
				xDK=IIF(FOUND(),tnd,'D')
				
				SELECT caseglm
				FOR m=1 TO 12
					vBulan=RIGHT('00'+ALLTRIM(STR(m)),2)				
					IF xDK='D'
						IF cDK='D'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)
							
							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ELSE
						IF cDK='K'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ENDIF 
					REPLACE Ang&vBulan	WITH Ang&vBulan + cAng(m)					
					REPLACE Eli&vBulan	WITH Eli&vBulan + cEli(m)					
				NEXT 				
			ENDIF 
			SELECT caseglm
			SET ORDER TO caseglm1
			SEEK nTahun+cAcc2
			IF FOUND()
				SELECT casecoa
				SET ORDER TO casecoa1
				SEEK cAcc2
				xDK=IIF(FOUND(),tnd,'D')
				
				SELECT caseglm
				FOR m=1 TO 12
					vBulan=RIGHT('00'+ALLTRIM(STR(m)),2)	
					IF xDK='D'
						IF cDK='D'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ELSE
						IF cDK='K'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ENDIF 
					REPLACE Ang&vBulan	WITH Ang&vBulan + cAng(m)					
					REPLACE Eli&vBulan	WITH Eli&vBulan + cEli(m)					
				NEXT 				
			ENDIF 
			SELECT caseglm
			SET ORDER TO caseglm1
			SEEK nTahun+cAcc1
			IF FOUND()
				SELECT casecoa
				SET ORDER TO casecoa1
				SEEK cAcc1
				xDK=IIF(FOUND(),tnd,'D')
				
				SELECT caseglm
				FOR m=1 TO 12
					vBulan=RIGHT('00'+ALLTRIM(STR(m)),2)
					IF xDK='D'
						IF cDK='D'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ELSE
						IF cDK='K'
							REPLACE Saw&vBulan	WITH Saw&vBulan + cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan + cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan + cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan + cSakP(m)
						ELSE 
							REPLACE Saw&vBulan	WITH Saw&vBulan - cSaw(m)
							REPLACE Sak&vBulan	WITH Sak&vBulan - cSak(m)

							REPLACE SawP&vBulan	WITH SawP&vBulan - cSawP(m)
							REPLACE SakP&vBulan	WITH SakP&vBulan - cSakP(m)
						ENDIF 
					ENDIF 
					REPLACE Ang&vBulan	WITH Ang&vBulan + cAng(m)					
					REPLACE Eli&vBulan	WITH Eli&vBulan + cEli(m)					
				NEXT 				
			ENDIF 
		ENDIF 
	ENDIF 
	SELECT csrCOA
	SKIP 
ENDDO 

****** Proses Saldo Buku Besar Pembantu
SELECT a.tgp,a.acc,a.kd_sup,a.deb,a.krd,a.typ FROM caseglt as a WHERE LEFT(DTOS(a.tgp),6)=nPerAktif ORDER BY a.acc,a.kd_sup INTO CURSOR csrGlmP
SELECT csrGlmP
GO TOP 
DO WHILE !EOF()
	cAcc=Acc
	cPer=LEFT(nPerAktif,4)
	cKdSup=kd_sup

	SELECT caseglmp
	SET ORDER TO CASEGLMP1   && PER+ACC+KD_SUP  
	SEEK cPer+cAcc+cKdSup
	IF !FOUND()
		APPEND BLANK
		REPLACE per 	WITH cPer
		REPLACE acc 	WITH cAcc
		REPLACE kd_sup 	WITH cKdSup	
	ENDIF 
	
	SELECT csrGlmP
	SKIP 
ENDDO 
******

IF nPerSis=nPerAktif AND nPerSis=nPer
	thisform.text1.Value='Proses Ganti Tanggal Sistem dan Pindah Bulan'
	
	IF nOpt=1  && Tutup Bulan Ya
		mAktif=zAktif
		SELECT fl_mem
		GO TOP 
		
		cNewDir=xDrvTrs+'\'+LEFT(DTOS(zAktif),6)
		
		mm= Month(zAktif)
		yy= Year(zAktif)
		IF mm = 12
		     mm= '01'
		     yy= ALLTRIM(STR(yy+1))
		ELSE 
		     mm= RIGHT('00'+ALLTRIM(STR(mm+1)),2)
		     yy= ALLTRIM(STR(yy))
		ENDIF 
		xaktifdate= CToD("01-" + mm + "-" + yy)
		xtglaktif= Left(DToS(xaktifdate), 6) && Periode Bulan Berikutnya
		IF RLOCK()
			REPLACE aktif_tgl WITH xaktifdate
			UNLOCK 
		ENDIF 	
		zAKTIF=xaktifdate	&& Ganti Variabel public bulan aktif

		cFileGlt=cNewDir+'\CASEGLT.DAT'
		cFileFin=cNewDir+'\CASE_FIN.DAT'
		cFileTam=cNewDir+'\CASE_TAM.DAT'
		cFilePjk=cNewDir+'\CASEPJK.DAT'

		IF !FILE(cFileGlt)
			MKDIR &cNewDir
		ENDIF 
		
		SELECT CASEGLT
		COPY TO &cfileglt FOR LEFT(DTOS(tgp),6)=LEFT(DTOS(mAktif),6) WITH CDX  

		SELECT CASEFIN
		COPY TO &cfileFin WITH CDX  

		SELECT CASETAM
		COPY TO &cfileTam WITH CDX  
		
		SELECT CASEPJK
		COPY TO &cfilePjk WITH CDX  
		
		SELECT caseglt
		DELETE ALL FOR LEFT(DTOS(tgp),6)<=LEFT(DTOS(mAktif),6)  && hapus data transaksi periode yang ditutup
		*ZAP 

		SELECT casefin
		DELETE ALL FOR LEFT(DTOS(tgp),6)<=LEFT(DTOS(mAktif),6)  && hapus data transaksi periode yang ditutup
		
		SELECT casetam
		DELETE ALL FOR LEFT(DTOS(tgp),6)<=LEFT(DTOS(mAktif),6)  && hapus data transaksi periode yang ditutup

		SELECT casepjk
		DELETE ALL FOR LEFT(DTOS(tgp),6)<=LEFT(DTOS(mAktif),6)  && hapus data transaksi periode yang ditutup
	ENDIF 
ENDIF 
MESSAGEBOX('Proses Tutup Bulan Selesai',0+64,'Perhatian')