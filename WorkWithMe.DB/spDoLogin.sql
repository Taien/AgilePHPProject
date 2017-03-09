--returns the GUID for the user if the login succeed, otherwise returns nothing

CREATE PROCEDURE [dbo].[spDoLogin]
	@Username nvarchar(50),
	@Password nvarchar(24)
AS
BEGIN
     SET NOCOUNT ON
     SELECT Id FROM [dbo].[tblUser] WHERE Username=@Username AND PasswordHash=HASHBYTES('SHA2_512', @Password+CAST(PasswordSalt AS NVARCHAR(36)))
END