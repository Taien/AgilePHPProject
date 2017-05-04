--Creates user and adds random salt to password.

CREATE PROCEDURE [dbo].[spCreateUser]
	@Username nvarchar(40),
	@Password nvarchar(24),
	@FirstName nvarchar(50),
	@MiddleInitial nvarchar(2),
	@LastName nvarchar(50),
	@Zip int,
	@Address nvarchar(50),
	@City nvarchar(50),
	@State nvarchar(2),
	@IsAddressPrivate bit,
	@EmailAddress nvarchar(255),
	@Response nvarchar(100) output
AS
BEGIN
	SET NOCOUNT ON

	IF EXISTS (SELECT * FROM [dbo].[tblUser] WHERE Username = @Username)
	BEGIN
		SET @Response='Username is taken.'
	    RETURN 0
	END
	IF EXISTS (SELECT * from [dbo].[tblUser] WHERE EmailAddress = @EmailAddress)
	BEGIN
	    SET @Response='Email address already exists.  Only one user per email please.'
	    RETURN 0
	END

    DECLARE @salt UNIQUEIDENTIFIER=NEWID()


    BEGIN TRY
	
        INSERT INTO [dbo].[tblUser] (Id, Username, PasswordHash, PasswordSalt, FirstName, MiddleInitial, LastName, EmailAddress, Zip, Address, IsAddressPrivate, UserImgId)
        VALUES(NewId(), @Username, HASHBYTES('SHA2_512', @Password+CAST(@salt AS NVARCHAR(36))), @salt, @FirstName, @MiddleInitial, @LastName, @EmailAddress, @Zip, @Address, @IsAddressPrivate, null);
		
		IF NOT EXISTS(SELECT TOP 1 s.Id FROM tblState s WHERE s.StateName = @State)
		    INSERT INTO [dbo].[tblState] (Id, StateName) VALUES (NewId(), @State)
	
		IF NOT EXISTS(SELECT TOP 1 c.Id FROM tblCity c WHERE c.Cityname = @City)
		    INSERT INTO [dbo].[tblCity] (Id, CityName) VALUES (NewId(), @City)
		
		DECLARE @cityId UNIQUEIDENTIFIER = (SELECT c.Id FROM tblCity c WHERE c.CityName = @City);
		DECLARE @stateId UNIQUEIDENTIFIER = (SELECT s.Id FROM tblState s WHERE s.StateName = @State);

		IF NOT EXISTS (SELECT TOP 1 z.Id FROM tblZip z WHERE z.Id = @Zip)
		    INSERT INTO [dbo].[tblZip] (Id, CityId, StateId) VALUES (@Zip, @cityId, @stateId)
		
        SET @Response='Successfully added user!'
		RETURN 1
    END TRY
    BEGIN CATCH
        SET @Response=ERROR_MESSAGE() 
		RETURN 0
    END CATCH
END