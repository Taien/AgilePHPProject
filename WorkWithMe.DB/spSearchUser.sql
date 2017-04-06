CREATE PROCEDURE [dbo].[spSearchUser]
	@SearchString nvarchar(255)
AS
	select Id, Username, FirstName, MiddleInitial, LastName, EmailAddress
	from tblUser
	where Username like '%' + @SearchString + '%' 
	or EmailAddress like '%' + @SearchString + '%'

