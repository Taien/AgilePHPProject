--Creates user and adds random salt to password.

CREATE PROCEDURE [dbo].[spCreateUser]
	@Username nvarchar(40),
	@Password nvarchar(24),
	@FirstName nvarchar(50),
	@MiddleInitial nvarchar(2),
	@Lastname nvarchar(50),
	@Zip int,
	@Address nvarchar(50),
	@IsAddressPrivate bit,
	@Response nvarchar(100) output
AS
BEGIN
	SET NOCOUNT ON

	IF EXISTS(SELECT * FROM [dbo].[tblUser] WHERE Username = @Username)
	BEGIN
		SET @Response='Username is taken.'
	    RETURN 0
	END
    DECLARE @salt UNIQUEIDENTIFIER=NEWID()
    BEGIN TRY

        INSERT INTO [dbo].[tblUser] (Id, Username, PasswordHash, PasswordSalt, FirstName, MiddleInitial, LastName, Zip, Address, IsAddressPrivate, UserImg)
        VALUES(NewId(), @Username, HASHBYTES('SHA2_512', @Password+CAST(@salt AS NVARCHAR(36))), @salt, @FirstName, @MiddleInitial, @LastName, @Zip, @Address, @IsAddressPrivate, null)

        SET @Response='Successfully added user!'
		RETURN 1
    END TRY
    BEGIN CATCH
        SET @Response=ERROR_MESSAGE() 
		RETURN 0
    END CATCH
END