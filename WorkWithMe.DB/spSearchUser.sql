CREATE PROCEDURE [dbo].[spSearchUser]
	@SearchString nvarchar(255),
	@OriginUserId uniqueidentifier
AS
	select Id, Username, FirstName, MiddleInitial, LastName, EmailAddress
	from tblUser
	where (Username like '%' + @SearchString + '%' 
	or EmailAddress like '%' + @SearchString + '%')
	and Id != @OriginUserId

