-- First make sure the image_url column exists
ALTER TABLE books ADD COLUMN IF NOT EXISTS image_url VARCHAR(500) NULL;

-- Update all books with image URLs (cycling through the list)
UPDATE books SET image_url = CASE 
    WHEN (id % 20) = 1 THEN 'https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 2 THEN 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 3 THEN 'https://images.unsplash.com/photo-1556229174-5e42a09e45af?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 4 THEN 'https://images.unsplash.com/photo-1607920592519-bab4d7db727b?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 5 THEN 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 6 THEN 'https://images.unsplash.com/photo-1612203985729-70726954388c?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 7 THEN 'https://images.unsplash.com/photo-1488477181946-6428a0291777?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 8 THEN 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 9 THEN 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 10 THEN 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 11 THEN 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 12 THEN 'https://images.unsplash.com/photo-1522184216316-3c25379f9760?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 13 THEN 'https://images.unsplash.com/photo-1518133835878-5a93cc3f89e5?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 14 THEN 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 15 THEN 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 16 THEN 'https://images.unsplash.com/photo-1630442923896-552f5a1e4868?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 17 THEN 'https://images.unsplash.com/photo-1541795795328-f073b763494e?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 18 THEN 'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 19 THEN 'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?q=80&w=800&auto=format&fit=crop'
    WHEN (id % 20) = 0 THEN 'https://images.unsplash.com/photo-1529042410759-befb1204b468?q=80&w=800&auto=format&fit=crop'
END;

-- Update specific books with themed images
UPDATE books SET image_url = 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop' WHERE id = 3;
UPDATE books SET image_url = 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop' WHERE id = 4;
UPDATE books SET image_url = 'https://images.unsplash.com/photo-1577308856961-8e9ec64d4e5a?q=80&w=800&auto=format&fit=crop' WHERE id = 5;
